<?php
require '../../db_connection.php';
require '../../../public/admin/check_login_admin.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$user = $data['username'];

try
{
    $sql = "select user_id, user, time, al.ip from access_logs al 
        left join accounts a on a.id = al.user_id
        where user like :user
        order by time desc
        limit 500;";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user', $user);
    $stmt->execute();
    $logins = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['logins' => $logins, 'success' => true]);
} 
catch(PDOException $e)
{
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

?>