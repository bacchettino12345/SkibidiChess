<?php
require '../../db_connection.php';
require '../../../public/admin/check_login_admin.php';
header('Content-Type: application/json');

try
{
    $sql = "SELECT user, MAX(time) as last_login FROM access_logs GROUP BY user";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $logins = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['logins' => $logins, 'success' => true]);
} 
catch(PDOException $e)
{
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

?>