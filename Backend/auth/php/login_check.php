<?php
    require '../../db_connection.php';
    header('Content-Type: application/json');
    $data = json_decode(file_get_contents("php://input"), true);
    session_start();

    if ($data) {
        $user = $data['username'];
        $psswd = hash("sha256", $data['password']);
    }
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
        if($result['admin'] == 1)
        {
            $_SESSION['admin'] = true;
        }
        else {
            unset($_SESSION['admin']);
        }
        echo true;
    } else
    {
        echo false;
    }

?>