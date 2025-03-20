<?php
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

?>
