<?php

require 'db_connection.php';

session_start();
if(isset($_SESSION['token'])){
    $token = $_SESSION['token'];
    $time = $_SESSION['time'];

    try
    {
        $sql = "SELECT * FROM tokens WHERE id = :token";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!$result)
        {
            session_destroy();
            $_SESSION = [];
        }
    } catch(PDOException $e)
    {
        echo $e->getMessage();
    }

    echo json_encode([
        'token' => $token,
        'time' => $time
    ]);
}
else
    echo "-1";
?>