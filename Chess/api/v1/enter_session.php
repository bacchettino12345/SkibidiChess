<?php

require 'db_connection.php';

header('Content-Type: application/json');
$data = json_decode(file_get_contents("php://input"), true);

session_start();
if ($data) {
    $token = $data['token'];
}
try
{
    $sql = "SELECT * FROM tokens WHERE id = :token";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':token', $token);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if($result)
    {
        $_SESSION['token'] = $result['id'];
        $_SESSION['time'] = $result['time'];
        echo json_encode([
            'token' => $result['id'],
            'time' => $result['time']
        ]);
    }
    else
    {
        echo "0";
    }
}
catch(PDOException $e)
{
    echo $e->getMessage();
}

?>