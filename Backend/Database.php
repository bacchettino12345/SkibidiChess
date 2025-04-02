<?php
namespace Skibidi\Database;

class Database
{
    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "chess";
    private $conn;

    public function __construct()
    {
        try {
            $this->conn = new \PDO(
                "mysql:host=$this->servername;dbname=$this->dbname", 
                $this->username, 
                $this->password
            );
            $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            error_log("Database connection error: " . $e->getMessage());
            die(json_encode([
                'success' => false,
                'message' => 'Internal database error'
            ]));
        }
    }

    public function getConnection()
    {
        return $this->conn;
    }
}