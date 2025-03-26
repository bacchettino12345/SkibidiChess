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
    if (empty($user) || empty($firstname) || empty($lastname) || empty($mail) || empty($psswd)) {
        echo json_encode(['success' => false]);
        exit();
    } else if (!preg_match("/^[a-zA-Z0-9_]*$/", $user)) {
        echo json_encode(['success' => false]);
        exit();
    } else if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false]);
        exit();
    }
    try
    {
        $sql = "INSERT INTO accounts (user, firstname, lastname, mail, psswd) VALUES (:user, :firstname, :lastname, :mail, :psswd)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user', $user);
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':mail', $mail);
        $stmt->bindParam(':psswd', $psswd);
        $stmt->execute(); 
        echo json_encode(['success' => true]);
    } catch (Exception $e)
    {
        echo json_encode(['success' => false]); 
    }
?>
