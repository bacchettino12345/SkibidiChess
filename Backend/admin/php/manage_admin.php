<?php
    require '../../db_connection.php';
    header('Content-Type: application/json');
    
    header('Content-Type: application/json');
    $data = json_decode(file_get_contents("php://input"), true);

    if ($data) {
        $user = $data['username'];
        $priv = $data['privilege'];
        try
        {
            $sql = "UPDATE accounts SET admin = :priv WHERE user = :user";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':user', $user);
            $stmt->bindParam(':priv', $priv);
            $stmt->execute();
            echo json_encode(['success' => true]);
        }
        catch(PDOException $e)
        {
            echo json_encode(['success' => false, 'error' => $e]);
        }
    }

?>