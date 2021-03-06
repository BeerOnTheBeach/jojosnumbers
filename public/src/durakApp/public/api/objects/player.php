<?php

class Player
{

    // database connection and table name
    private $conn;
    private $table_name = "players";

    // object properties
    public $id;
    public $name;
    public $losses;
    public $draws;
    public $gamescount;
    public $elo;
    public $color;
    public $currentlyPlaying;
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
                name=:name, losses=:losses, draws=:draws, elo=:elo, color=:color, gamescount=:gamescount, currentlyPlaying=:currentlyPlaying, created=:created";

        // prepare query
        $stmt = $this->conn->prepare($query);
        var_dump($this);
        // sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->losses = htmlspecialchars(strip_tags($this->losses));
        $this->draws = htmlspecialchars(strip_tags($this->draws));
        $this->gamescount = htmlspecialchars(strip_tags($this->gamescount));
        $this->elo = htmlspecialchars(strip_tags($this->elo));
        $this->color = htmlspecialchars(strip_tags($this->color));
        $this->currentlyPlaying = htmlspecialchars(strip_tags($this->currentlyPlaying));
        $this->created = htmlspecialchars(strip_tags($this->created));

        // bind values
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":losses", $this->losses);
        $stmt->bindParam(":draws", $this->draws);
        $stmt->bindParam(":color", $this->color);
        $stmt->bindParam(":gamescount", $this->gamescount);
        $stmt->bindParam(":elo", $this->elo);
        $stmt->bindParam(":currentlyPlaying", $this->currentlyPlaying);
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
        $this->name = $row['name'];
        $this->losses = $row['losses'];
        $this->draws = $row['draws'];
        $this->gamescount = $row['gamescount'];
        $this->elo = $row['elo'];
        $this->color = $row['color'];
        $this->currentlyPlaying = $row['currentlyPlaying'];
        $this->created = $row['created'];
    }

    // update the product
    function update(){
        mysqli_report(MYSQLI_REPORT_ALL);
        // update query
        $query = "UPDATE
                " . $this->table_name . "
            SET
                name = :name,
                losses = :losses,
                draws = :draws,
                gamescount = :gamescount,
                elo = :elo,
                color = :color,
                currentlyPlaying = :currentlyPlaying
            WHERE
                id = :id";
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->id));
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->losses=htmlspecialchars(strip_tags($this->losses));
        $this->draws=htmlspecialchars(strip_tags($this->draws));
        $this->gamescount=htmlspecialchars(strip_tags($this->gamescount));
        $this->elo=htmlspecialchars(strip_tags($this->elo));
        $this->color=htmlspecialchars(strip_tags($this->color));
        $this->currentlyPlaying = htmlspecialchars(strip_tags($this->currentlyPlaying));

        // bind new values
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':losses', $this->losses);
        $stmt->bindParam(':draws', $this->draws);
        $stmt->bindParam(':gamescount', $this->gamescount);
        $stmt->bindParam(':elo', $this->elo);
        $stmt->bindParam(':color', $this->color);
        $stmt->bindParam(':currentlyPlaying', $this->currentlyPlaying);

        // execute the query
        if($stmt->execute()){
            return true;
        }
        var_dump($stmt->errorInfo());
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