<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/database.php';
include_once '../objects/game.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

// initialize object
$game = new Game($db);

// query products
$stmt = $game->read();
$num = $stmt->rowCount();

// check if more than 0 record found
if($num>0){

    // products array
    $game_arr=array();
    $game_arr["records"]=array();

    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);

        $game_item=array(
            "id" => $id,
            "loser" => $loser,
            "loser_2" => $loser_2,
            "players" => $players,
            "session_id" => $session_id,
            "modified" => $modified,
        );

        array_push($game_arr["records"], $game_item);
    }

    // set response code - 200 OK
    http_response_code(200);

    // show products data in json format
    echo json_encode($game_arr);
} else{

    // set response code - 404 Not found
    http_response_code(404);

    // tell the user no products found
    echo json_encode(
        array("message" => "No games found.")
    );
}