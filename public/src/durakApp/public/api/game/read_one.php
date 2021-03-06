<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

// include database and object files
include_once '../config/database.php';
include_once '../objects/game.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare product object
$game = new Game($db);

// set ID property of record to read
$game->id = isset($_GET['id']) ? $_GET['id'] : die();

// read the details of product to be edited
$game->readOne();

if($game->name!=null){
    // create array
    $game_arr = array(
        "id" =>  $game->id,
        "loser" => $game->loser,
        "loser_2" => $game->loser_2,
        "players" => $game->players,
        "session_id" => $game->session_id
    );

    // set response code - 200 OK
    http_response_code(200);

    // make it json format
    echo json_encode($game_arr);
}

else{
    // set response code - 404 Not found
    http_response_code(404);

    // tell the user product does not exist
    echo json_encode(array("message" => "Game does not exist."));
}
?>