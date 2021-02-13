<?php

use durak\durakController;
require ("durakController.php");
$durakApp = new durakController();
$durakApp->init();

if (array_key_exists('submitGame', $_POST)) {
    $durakApp->submitGame();
}
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
    <link rel="stylesheet" href="../css/main.css">

    <!-- Latest compiled and minified JavaScript -->
    <script src="../js/jquery-3.5.1.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="durak.js"></script>
</head>
<body>
<form method="post">
    <table class='table table-hover'>
        <tr>
            <th>Datum</th>
            <th>Anzahl</th>
            <?php
                echo $durakApp->playerButtons;
            ?>
            <th><div id="btn-show" class="btn btn-info">Show more ...</div></th>
            <th><div id="btn-delete" class="btn btn-danger">Delete last</div></th>
        </tr>
        <?php
            echo $durakApp->csvHtml;
        ?>
    </table>
</form>
</body>
</html>
