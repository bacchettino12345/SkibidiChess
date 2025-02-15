<?php
    require "../../db_connection.php";
    header('Content-Type: application/json');
    $data = json_decode(file_get_contents("php://input"), true);

    if ($data) {
        $user = $data['username'];
    }
    try
    {
        $stmt = $conn->prepare("DELETE FROM accounts WHERE user = :user");
        $stmt->bindParam(':user', $user);
        $stmt->execute();
        echo json_encode(['success' => true]);
    }
    catch(PDOException $e)
    {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
?>