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
include_once '../objects/game.php';

$database = new Database();
$db = $database->getConnection();

$game = new Game($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
if(
    !empty($data->loser) &&
    !empty($data->loser_2) &&
    !empty($data->players) &&
    !empty($data->session_id)
){

    // set product property values
    $game->loser = $data->loser;
    $game->loser_2 = $data->loser_2;
    $game->players = $data->players;
    $game->session_id = $data->session_id;
    $game->created = date('Y-m-d H:i:s');

    // create the product
    if($game->create()){

        // set response code - 201 created
        http_response_code(201);

        // tell the user
        echo json_encode(array("message" => "Game was created."));
    }

    // if unable to create the product, tell the user
    else{

        // set response code - 503 service unavailable
        http_response_code(503);

        // tell the user
        echo json_encode(array("message" => "Unable to create game."));
    }
}

// tell the user data is incomplete
else{

    // set response code - 400 bad request
    http_response_code(400);

    // tell the user
    echo json_encode(array("message" => "Unable to create game. Data is incomplete."));
}
?>
