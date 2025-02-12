<?php
    
require '../../db_connection.php';
if(!isset($_SESSION))
{
    session_start();
}
try
{
    $sql = "DELETE FROM tokens WHERE id = :token";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':token', $_SESSION['token']);
    $_SESSION['token'] = null;
    unset($_SESSION['token']);
    $stmt->execute();
}
catch(PDOException $e)
{
    echo $e->getMessage();
}

?>