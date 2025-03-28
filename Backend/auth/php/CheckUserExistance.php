<?php
    // IMPLEMENATRE IL CONTROLLLO UTENTE + MAIIL, MAIL, UTENTE IN BASE AL JSON
    require "../../db_connection.php";

    header('Content-Type: application/json');

    $data = json_decode(file_get_contents("php://input"), true);

    if($data)
    {
        $mail = $data['email'];
    }
    try
    {
        $sql = "SELECT * FROM accounts WHERE mail = :mail";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':mail', $mail);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if($result)
        {
            echo json_encode(['exists' => true, 'success' => true]);
        }else
        {
            echo json_encode(['exists' => false, 'success' => true]);
        }
    }catch(PDOException $e)
    {
        echo json_encode(['succcess' => false, 'error', $e]);
    }

?>