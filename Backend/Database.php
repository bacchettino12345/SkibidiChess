<?php

    class Database
    {
        private $servername = "localhost";
        private $username = "root";
        private $password = "";
        private $dbname = "chess";

        private $conn;

        public function __construct()
        {
            try
            {
                $this->conn = new PDO("mysql:host=$this->servername;dbname=$this->dbname", $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } 
            catch (Exception $e)
            {
                error_log("Errore di connessione al database: " . $e->getMessage());
                die("Errore interno di connessione al Database.");
            }
        }

        public function getConnection()
        {
            return $this->conn;
        }

    }


    /* OLD
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "chess";

    try
    {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } 
    catch (Exception $e)
    {
        error_log("Errore di connessione al database: " . $e->getMessage());
        die("Errore interno di connessione al Database.");
    }
    */
?>
