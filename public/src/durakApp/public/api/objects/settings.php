<?php

class Settings
{

    // database connection and table name
    private $conn;
    private $table_name = "settings";

    // object properties
    public $id;

    // constructor with $db as database connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // read settings
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

    // update the settings
    function update(){
        mysqli_report(MYSQLI_REPORT_ALL);
        // update query
        $query = "UPDATE
                " . $this->table_name . "
            SET
                id = :id";
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->id));

        // bind new values
        $stmt->bindParam(':id', $this->id);

        // execute the query
        if($stmt->execute()){
            return true;
        }
        return false;
    }

}

?>