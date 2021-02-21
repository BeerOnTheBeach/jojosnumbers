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
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Durak App</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/bootstrap-select.css">
    <link rel="stylesheet" href="../css/durakApp.css">

    <!-- Latest compiled and minified JavaScript -->
    <script src="../js/jquery-3.5.1.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/bootstrap-select.min.js"></script>
    <script src="durak.js"></script>
</head>
<body>
<?php include '../snippets/header.php'; ?>
<div class="container-fluid durak-app-container">
<form method="post">
    <div class="col-md-9">
        <h2 class="text-center">Letzten 10 Loses</h2>
        <div class="settings-header">Unentschieden eintragen</div>
        <select title="Unentschiedene wählen" class="selectpicker" name="playerDraw[]" id="player-draw" multiple>
            <?php
            //Render player-multiselect fpr draw
            foreach ($durakApp->playerPresent as $key => $player) {
                echo "<option name='submitDraw' value='$player'>$player</option>";
            }
            ?>
        </select>
        <button title="Unentschieden bestätigen" id="btn-draw-submit" class="btn btn-warning">Bestätigen</button>
        <table class='table table-hover'>
        <thead>
            <tr>
                <th>Datum</th>
                <th>Anzahl</th>
                <?php
                //Render player-buttons
                foreach ($durakApp->player as $key => $player) {
                    $btnClass = '';
                    if($key >= $durakApp->amountHidden) break;
                    foreach ($durakApp->playerPresent as $playerPresent) {
                        if($player == $playerPresent) {
                            $btnClass = "btn-primary'";
                            break;
                        } else {
                            $btnClass = "btn-secondary' disabled";
                        }
                    }
                    echo "<th class=''><button title='Lose für diesen Spieler eintragen' type='submit' class='btn-player btn $btnClass name='submitGame' value='$player'>$player</button></th>";
                }
                ?>
            </tr>
        </thead>
        <?php
            echo $durakApp->csvHtml;
        ?>
    </table>
    </div>
    <div class="col-md-3">
        <h2>Einstellungen</h2>
        <div class="settings-item">
            <div class="settings-header">Anwesende Spieler wählen</div>
            <select title="Anwesende Spieler wählen" class="selectpicker" name="playerPresent[]" id="player-present" multiple>
                <?php
                foreach ($durakApp->player as $key => $player) {
                    echo "<option name='playerPresent' value='$player'>$player</option>";
                }
                ?>
            </select>
        <button title="Anwesenheit bestätigen" id="btn-draw-present" class="btn btn-info">Bestätigen</button>
        </div>
        <div class="settings-item">
            <div class="settings-header">Häufige Settings</div>
            <button title="Zeigt die ersten 5 Spieler" value="5" name="amountHidden" id="btn-show-gettho" class="btn btn-info">EFA</button>
            <button title="Zeigt alle Spieler" value="100" name="amountHidden" id="btn-show-all" class="btn btn-info">Alle</button>
        </div>
        <div class="settings-item">
            <div class="settings-header">Weitere Funktionen</div>
            <a id="btn-draw-download-csv" class="btn btn-success" href="\src\statistics\durak\durak.csv">Download csv</a>
            <button id="btn-delete" class="btn btn-danger" name="deleteGame">Delete last</button>
        </div>
        <div class="settings-item">
            <div class="settings-header">Info</div>
            <div>Letztes Spiel:</div>
            <span class="badge"><?php echo $durakApp->timeLastGameSubmitted ?></span>
        </div>
    </div>
</form>
</div>
</body>
</html>
