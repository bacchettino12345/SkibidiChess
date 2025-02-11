<?php
require 'php/db_connection.php';

$user = $_POST['user'];
$psswd = hash('sha256', $_POST['psswd']);

    session_start();
    session_destroy();
    $_SESSION = [];
    session_start();
    $sql = "SELECT * FROM accounts WHERE user = :user AND psswd = :psswd";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user', $user);
    $stmt->bindParam(':psswd', $psswd);
    $stmt->execute();
    echo "ziocane";
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if($result)
    {
        $_SESSION['user'] = $user;
        $_SESSION['psswd'] = $psswd;
        header('Location: ./index.php');
    } else
    {
        header('Location: ./login.html');
    }


?>