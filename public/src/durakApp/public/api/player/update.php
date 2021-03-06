<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and object files
include_once '../config/database.php';
include_once '../objects/player.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare product object
$player = new Player($db);

// get id of product to be edited
$data = json_decode(file_get_contents("php://input"));

// set ID property of product to be edited
$player->id = $data->id;

// set product property values
$player->name = $data->name;
$player->losses = $data->losses;
$player->draws = $data->draws;
$player->gamescount = $data->gamescount;
$player->elo = $data->elo;
$player->color = $data->color;
$player->currentlyPlaying = $data->currentlyPlaying;

// update the product
if($player->update()){

    // set response code - 200 ok
    http_response_code(200);

    // tell the user
    echo json_encode(array("message" => "Player was updated."));
}

// if unable to update the product, tell the user
else{

    // set response code - 503 service unavailable
    http_response_code(503);

    // tell the user
    echo json_encode(array("message" => "Unable to update player."));
}
?>