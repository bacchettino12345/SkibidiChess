<?php
    require "../../db_connection.php";
    header('Content-Type: application/json');
    $data = json_decode(file_get_contents("php://input"), true);
    session_start();

    if ($data) {
        $user = $data['username'];
    }
    try
    {
        $stmt = $pdo->prepare("DELETE FROM accounts WHERE user = :user");
        $stmt->bindParam(':user', $user);
        $stmt->execute();
    }
    catch(PDOException $e)
    {
        echo $e;
    }

?>