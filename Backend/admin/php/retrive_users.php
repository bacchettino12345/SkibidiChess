<?php
    require '../../db_connection.php';
    header('Content-Type: application/json');
    
    header('Content-Type: application/json');
    $data = json_decode(file_get_contents("php://input"), true);

    if ($data) {
        $user = $data['username'];
        try
        {
            $seachParam = "%" . $user . "%";
            $sql = "SELECT * FROM accounts WHERE user LIKE :search";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':search', $seachParam, PDO::PARAM_STR);
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['users' => $users, 'success' => true]);
        } 
        catch(PDOException $e)
        {
            echo json_encode(['success' => false, 'error' => $e]);
        }
    }
    
?>