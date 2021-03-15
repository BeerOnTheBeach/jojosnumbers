<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

// include database and object files
include_once '../config/database.php';
include_once '../objects/player.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare product object
$player = new Player($db);

// set ID property of record to read
$player->id = isset($_GET['id']) ? $_GET['id'] : die();

// read the details of product to be edited
$player->readOne();

if($player->name!=null){
    // create array
    $player_arr = array(
        "id" =>  $player->id,
        "name" => $player->name,
        "losses" => $player->losses,
        "draws" => $player->draws,
        "gamescount" => $player->gamescount,
        "elo" => $player->elo,
        "color" => $player->color,
        "currentlyPlaying" => $player->currentlyPlaying,
    );

    // set response code - 200 OK
    http_response_code(200);

    // make it json format
    echo json_encode($player_arr);
}

else{
    // set response code - 404 Not found
    http_response_code(404);

    // tell the user product does not exist
    echo json_encode(array("message" => "Player does not exist."));
}
?>