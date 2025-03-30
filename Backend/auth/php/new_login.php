<?php
    require '../../db_connection.php';
    header('Content-Type: application/json');
    $data = json_decode(file_get_contents("php://input"), true);
    session_start();
    if ($data) {
        $user = trim($data['username']);
        $firstname = $data['firstname'];
        $lastname = $data['lastname'];
        $mail = $data['email'];
        $psswd = hash("sha256", $data['password']);
    }
    session_destroy();
    $_SESSION = [];
    
?>
