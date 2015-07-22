<?php

//start connection with DB
$db = new mysqli("localhost", "root", "root", "intro_to_php");
//connection error
if($db->connect_errno) {
    echo $db->connect_error;
    exit();
}

//Prepare INSERT query
//$db->prepare method loads the INSERT statement
//$stmt holds the query for the connected database $db.
$add_stmt = $db->prepare('INSERT INTO my_maps_app (address, date) VALUES (?,?)');

//Prepare DELETE query
$del_stmt = $db->prepare('DELETE FROM my_maps_app WHERE id=?');

//? is the value to be passed, acts as a placeholder in the prepare statement
//Set up $item variable to hold string to be passed into todo_list
$address = '';
$date = '';
$id = '';

//bind the $item variable to the ? parameter in the query
$add_stmt->bind_param('ss', $address, $date);
$del_stmt->bind_param('i', $id);

if(isset($_POST['submit'])) {
    $address = $_POST['marker'];
    $date = date('D, j M Y');
    $add_stmt->execute();
}


//Delete item
if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $del_stmt->execute();

}

//obtain table data
//set up SELECT query
//prepare method not needed since it is reading data
$sel = "SELECT * FROM my_maps_app";
//use the unsecured method ->query() to read data form $db
$result = $db->query($sel);

?>

<!DOCTYPE html>
<html>
    <head>
        <title>My Maps App</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="https://storage.googleapis.com/code.getmdl.io/1.0.0/material.light_green-amber.min.css">
        <script src="https://storage.googleapis.com/code.getmdl.io/1.0.0/material.min.js"></script>
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link rel="stylesheet" href="style.css" type="text/css">
    </head>
    <body>
        <div class="mdl-grid">
            <div class="mdl-cell mdl-cell--2-col"></div>
            <div class="mdl-card mdl-shadow--4dp mdl-cell mdl-cell--4-col">
                <div class="mdl-card__title mdl-card--border"><h2 class="mdl-card__title-text">My Map</h2></div>
                <div class="mdl-card__media mdl-card--border">
                    <img src="https://maps.googleapis.com/maps/api/staticmap?size=800x800&markers=
                    <?php
                    foreach($result as $row) {
                        echo $row['address'] . "|";
                    }
                    ?>">
                </div>
                <div></div>
            </div>
            <div class="mdl-card mdl-shadow--4dp mdl-cell mdl-cell--4-col">
                <div class="mdl-card__title mdl-card--border"><h2 class="mdl-card__title-text">My Locations</h2></div>
                <div class="mdl-card__supporting-text mdl-card--border">
                    <table class="mdl-data-table mdl-js-data-table">
                        <thead>
                            <tr>
                                <th class="mdl-data-table__cell--non-numeric">Marker</th>
                                <th class="mdl-data-table__cell--non-numeric">Date Added</th>
                                <th class="mdl-data-table__cell--non-numeric">Remove</th>
                            </tr>
                        </thead>

                        <?php
                        foreach($result as $row) { ?>
                            <tr>
                                <td class="mdl-data-table__cell--non-numeric"><?php echo $row['address']?></td>
                                <td class="mdl-data-table__cell--non-numeric"><?php echo $row['date']?></td>
                                <td class="mdl-data-table__cell--non-numeric">
                                    <form action="my_maps_app.php" method="post">
                                        <input type="submit" name="delete" value="DELETE" class="mdl-button mdl-js-button mdl-button--primary">
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>

                    </table>
                </div>
                <div class="mdl-card__actions mdl-card--border">
                    <form action="my_maps_app.php" method="post">
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="text" name="marker" id="add_marker">
                            <label class="mdl-textfield__label" for="add_marker">Add Location...</label>
                        </div>
                        <input type="submit" name="submit" value="GO!" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">
                    </form>
                </div>
            </div>
            <div class="mdl-cell mdl-cell--2-col"></div>
        </div>
    </body>
</html>