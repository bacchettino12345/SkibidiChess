<?php
require '../../db_connection.php';
require '../../../public/admin/check_login_admin.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$user = $data['username'];



?>