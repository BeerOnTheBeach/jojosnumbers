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
    <link rel="stylesheet" href="../css/durak.css">

    <!-- Latest compiled and minified JavaScript -->
    <script src="../js/jquery-3.5.1.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="durak.js"></script>
</head>
<body>
<?php include '../snippets/header.php'; ?>
<form method="post">
    <div class="col">
        <div onclick="showPlayerPresent()" id="btn-player-present" class="btn btn-warning">Player present</div>
    </div>
    <div class="col container-player-present hidden">
        <select name="playerPresent[]" id="player-present" multiple>
            <?php
            foreach ($durakApp->player as $key => $player) {
                echo "<option class='btn btn-primary' name='playerPresent' value='$player'>$player</option>";
            }
            ?>
        </select>
        <button id="btn-draw-submit" class="btn btn-warning">Submit</button>
    </div>
</form>
<form method="post">
    <div class="col">
        <div onclick="showDraw()" id="btn-draw" class="btn btn-warning">Draw</div>
    </div>
    <div class="col container-draw hidden">
        <select name="playerDraw[]" id="player-draw" multiple>
            <?php
            //Render player-multiselect fpr draw
            foreach ($durakApp->playerPresent as $key => $player) {
                echo "<option class='btn btn-primary' name='submitDraw' value='$player'>$player</option>";
            }
            ?>
        </select>
        <button id="btn-draw-submit" class="btn btn-warning">Submit</button>
    </div>
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
                <button id="btn-delete" class="btn btn-danger" name="deleteGame">Delete last</button>
            </th>
        </tr>
        <?php
        echo $durakApp->csvHtml;
        ?>
    </table>
</form>
</body>
</html>
