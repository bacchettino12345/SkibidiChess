<?php
    require '../db_connection.php';
    // MODIFICARE PER FUNZIONAMENTO CON AJAX
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
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if($result)
    {
        $_SESSION['user'] = $user;
        $_SESSION['psswd'] = $psswd;
        echo 1;
    } else
    {
        echo 0;
    }

?>