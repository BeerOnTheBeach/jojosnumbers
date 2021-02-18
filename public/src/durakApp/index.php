<?php

use durak\durakController;

require("durakController.php");
$durakApp = new durakController();

if (!isset($_SESSION)) {
    session_start();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['postdata'] = $_POST;
    $durakApp->init();
    $durakApp->handleFormData();
    unset($_POST); //unsetting $_POST Array
    header("Location: " . $_SERVER['REQUEST_URI']); //This will let your uri parameters to still exist
    exit;
}
$durakApp->init();

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Durak App</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/bootstrap-select.css">

    <!-- Latest compiled and minified JavaScript -->
    <script src="../js/jquery-3.5.1.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/bootstrap-select.min.js"></script>
    <script src="durak.js"></script>
</head>
<body>
<?php include '../snippets/header.php'; ?>
<div class="container-fluid durak-app-container">
<form class="form form-player-present" method="post">
    <label class="label label-warning" for="player-present">Player present: </label>
    <select class="selectpicker" name="playerPresent[]" id="player-present" multiple>
        <?php
        foreach ($durakApp->player as $key => $player) {
            echo "<option name='playerPresent' value='$player'>$player</option>";
        }
        ?>
    </select>
    <button id="btn-draw-submit" class="btn btn-warning">Submit</button>
    <a id="btn-draw-download-csv" class="btn btn-success" href="\src\statistics\durak\durak.csv">Download csv</a>
</form>
<form method="post">
    <table class='table table-hover'>
        <tr>
            <th>Datum</th>
            <th>Anzahl</th>
            <?php
            //Render player-buttons
            foreach ($durakApp->playerPresent as $key => $player) {
                echo "<th class=''><button type='submit' class='btn-player btn btn-primary' name='submitGame' value='$player'>$player</button></th>";
            }
            ?>
            <th>
                <select class="selectpicker" name="playerDraw[]" id="player-draw" multiple>
                    <?php
                    //Render player-multiselect fpr draw
                    foreach ($durakApp->playerPresent as $key => $player) {
                        echo "<option name='submitDraw' value='$player'>$player</option>";
                    }
                    ?>
                </select>
                <button id="btn-draw-submit" class="btn btn-warning">Submit Draw</button>
            </th>

            <th>
                <button id="btn-delete" class="btn btn-danger" name="deleteGame">Delete last</button>
            </th>
        </tr>
        <?php
            echo $durakApp->csvHtml;
        ?>
    </table>
</form>
</div>
</body>
</html>
