<?php

class Database
{
    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "tams";
    private $conn;

    // Constructor to initialize the database connection
    public function __construct()
    {
        $this->connect();
    }

    // Establish a database connection
    private function connect()
    {
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);

        // Check for connection errors
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    // Get the database connection
    public function getConnection()
    {
        return $this->conn;
    }

    // Close the database connection
    public function closeConnection()
    {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
?>
