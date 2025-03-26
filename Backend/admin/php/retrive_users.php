<?php
    require '../../db_connection.php';
    header('Content-Type: application/json');
    
    $data = json_decode(file_get_contents("php://input"), true);

    if ($data) {
        $user = $data['username'];
        try
        {
            $seachParam = "%" . $user . "%"; // AGGIUNGERE IP ULTIMO ACCESSO
            $sql = "SELECT id, user, mail, active, admin, pts, MAX(la.time) as last_login FROM accounts a 
                left join access_logs la on la.user_id = a.id
                WHERE user like :search OR mail like :search OR firstname like :search OR lastname like :search";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':search', $seachParam, PDO::PARAM_STR);
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['users' => $users, 'success' => true]);
        } 
        catch(PDOException $e)
        {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }
    
?>