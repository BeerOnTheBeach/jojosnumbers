<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// get database connection
include_once '../config/database.php';

// instantiate product object
include_once '../objects/player.php';

$database = new Database();
$db = $database->getConnection();

$player = new Player($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
if(
    isset($data->name) &&
    isset($data->losses) &&
    isset($data->draws) &&
    isset($data->elo) &&
    isset($data->color) &&
    isset($data->currentlyPlaying)
){

    // set product property values
    $player->name = $data->name;
    $player->losses = $data->losses;
    $player->draws = $data->draws;
    $player->gamescount = $data->gamescount;
    $player->elo = $data->elo;
    $player->color = $data->color;
    $player->currentlyPlaying = $data->currentlyPlaying;
    $player->created = date('Y-m-d H:i:s');

    // create the product
    if($player->create()){

        // set response code - 201 created
        http_response_code(201);

        // tell the user
        echo json_encode(array("message" => "Player was created."));
    }

    // if unable to create the product, tell the user
    else{

        // set response code - 503 service unavailable
        http_response_code(503);

        // tell the user
        echo json_encode(array("message" => "Unable to create player."));
    }
}

// tell the user data is incomplete
else{

    // set response code - 400 bad request
    http_response_code(400);

    // tell the user
    echo json_encode(array("message" => "Unable to create player. Data is incomplete."));
}
?>
