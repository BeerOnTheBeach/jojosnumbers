<?php

class Game
{

    // database connection and table name
    private $conn;
    private $table_name = "games";

    // object properties
    public $id;
    public $loser;
    public $loser_2;
    public $players;
    public $session_id;
    public $created;

    // constructor with $db as database connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // read products
    function read()
    {

        // select all query
        $query = "SELECT * FROM " . $this->table_name;

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        return $stmt;
    }

    // create product
    function create()
    {

        // query to insert record
        $query = "INSERT INTO
                " . $this->table_name . "
            SET
                loser=:loser, loser_2=:loser_2, players=:players, session_id=:session_id, created=:created";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->loser = htmlspecialchars(strip_tags($this->loser));
        $this->loser_2 = htmlspecialchars(strip_tags($this->loser_2));
        $this->players = htmlspecialchars(strip_tags($this->players));
        $this->session_id = htmlspecialchars(strip_tags($this->session_id));
        $this->created = htmlspecialchars(strip_tags($this->created));

        // bind values
        $stmt->bindParam(":loser", $this->loser);
        $stmt->bindParam(":loser_2", $this->loser_2);
        $stmt->bindParam(":players", $this->players);
        $stmt->bindParam(":session_id", $this->session_id);
        $stmt->bindParam(":created", $this->created);

        // execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // used when filling up the update product form
    function readOne(){

        // query to read single record
        $query = "SELECT *
            FROM
                " . $this->table_name . "
            WHERE
                id = ?
            LIMIT
                0,1";

        // prepare query statement
        $stmt = $this->conn->prepare( $query );

        // bind id of product to be updated
        $stmt->bindParam(1, $this->id);

        // execute query
        $stmt->execute();

        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set values to object properties
        $this->loser = $row['loser'];
        $this->loser_2 = $row['loser_2'];
        $this->players = $row['players'];
        $this->session_id = $row['session_id'];
    }

    // update the product
    function update(){

        // update query
        $query = "UPDATE
                " . $this->table_name . "
            SET
                loser = :loser,
                loser_2 = :loser_2,
                players = :players,
                session_id = :session_id
            WHERE
                id = :id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->loser=htmlspecialchars(strip_tags($this->loser));
        $this->loser_2=htmlspecialchars(strip_tags($this->loser_2));
        $this->players=htmlspecialchars(strip_tags($this->players));
        $this->id=htmlspecialchars(strip_tags($this->id));

        // bind new values
        $stmt->bindParam(':losses', $this->loser);
        $stmt->bindParam(':loser_2', $this->loser_2);
        $stmt->bindParam(':players', $this->players);
        $stmt->bindParam(':session_id', $this->id);

        // execute the query
        if($stmt->execute()){
            return true;
        }

        return false;
    }

    // delete the product
    function delete(){

        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->id));

        // bind id of record to delete
        $stmt->bindParam(1, $this->id);

        // execute query
        if($stmt->execute()){
            return true;
        }

        return false;
    }

}

?>