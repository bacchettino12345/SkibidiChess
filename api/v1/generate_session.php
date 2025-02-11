<?php

require 'db_connection.php';

session_start();
$token = random_int(100000, 999999);
$_SESSION['token'] = $token;
$time = time();
$_SESSION['time'] = $time;

try{
    $sql = "INSERT INTO tokens (id, date, time) VALUES (:token, NOW(), :time)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':token', $token);
    $stmt->bindParam(':time', $time);
    $stmt->execute();
    
    echo json_encode([
        'token' => $token,
        'time' => $time
    ]);
}
catch(PDOException $e){
    echo $e->getMessage();
}