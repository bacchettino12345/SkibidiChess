<?php
    require '../../db_connection.php';
    header('Content-Type: application/json');
    
    header('Content-Type: application/json');
    $data = json_decode(file_get_contents("php://input"), true);

    if ($data) {
        $user = $data['username'];
        $pts = $data['points'];
        try
        {
            $sql = "UPDATE accounts SET pts = :pts WHERE user = :user";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':user', $user);
            $stmt->bindParam(':pts', $pts);
            $stmt->execute();
            echo json_encode(['success' => true]);
        }
        catch(PDOException $e)
        {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

?>