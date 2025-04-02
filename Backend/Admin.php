<?php

namespace Skibidi;

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/EmailSender.php';
require_once __DIR__ . '/SessionChecker.php';

use PDO;
use PDOException;

class Admin
{
    private $conn;
    private $email;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
        $this->email = new EmailSender();
    }

    public function getAccessLogsForUser($username)
    {
        try {
            $sql = "SELECT user_id, username, time, al.ip, isp, country, region, device, os, client 
                    FROM access_logs al 
                    LEFT JOIN accounts a ON a.id = al.user_id
                    WHERE username LIKE :username
                    ORDER BY time DESC
                    LIMIT 500";

            $stmt = $this->conn->prepare($sql);
            $searchUsername = "%$username%";
            $stmt->bindParam(':username', $searchUsername, PDO::PARAM_STR);
            $stmt->execute();

            echo json_encode([
                'success' => true,
                'logins' => $stmt->fetchAll(PDO::FETCH_ASSOC)
            ]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Database error']);
        }
    }

    public function getAccessLogs()
    {
        try {
            $sql = "SELECT user_id, username, time, al.ip, isp, country, region, device, os, client 
                    FROM access_logs al 
                    LEFT JOIN accounts a ON a.id = al.user_id
                    ORDER BY time DESC
                    LIMIT 500";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute();

            echo json_encode([
                'success' => true,
                'logins' => $stmt->fetchAll(PDO::FETCH_ASSOC)
            ]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Database error']);
        }
    }
}




header('Content-Type: application/json');
$data = json_decode(file_get_contents("php://input"), true);

$sessionChecker = new SessionChecker();

if($sessionChecker->checkSession())
    if($sessionChecker->checkAdmin())
    {
        $admin = new Admin();
        switch ($data['action']) {
            case 'getAccessLogsForUser':
                $response = $admin->getAccessLogsForUser($data['username']);
                break;
            case 'getAccessLogs':
                $response = $admin->getAccessLogs();
                break;
            default:
                echo json_encode(['success' => false, 'message' => 'Invalid action.']);
                break;
        }
    }
else
    echo json_encode(['success' => false, 'message' => 'Auth error.']);
