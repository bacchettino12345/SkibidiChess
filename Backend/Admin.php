<?php
namespace Skibidi;

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/EmailSender.php';
require_once __DIR__ . '/SessionChecker.php';

use Skibidi\Database\Database;
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
        $this->email = new \EmailSender();
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
            
            return [
                'success' => true,
                'logins' => $stmt->fetchAll(PDO::FETCH_ASSOC)
            ];
            
        } catch (PDOException $e) {
            error_log("Access logs error: " . $e->getMessage());
            return ['success' => false, 'message' => 'Database error'];
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
            
            return [
                'success' => true,
                'logins' => $stmt->fetchAll(PDO::FETCH_ASSOC)
            ];
            
        } catch (PDOException $e) {
            error_log("Access logs error: " . $e->getMessage());
            return ['success' => false, 'message' => 'Database error'];
        }
    }
}

// Main execution
header('Content-Type: application/json');

try {
    $sessionChecker = new SessionChecker();
    
    if(!$sessionChecker->checkSession()) {
        throw new \RuntimeException('You are not logged in.');
    }
    
    if(!$sessionChecker->checkAdmin()) {
        throw new \RuntimeException('You are not admin.');
    }

    $data = json_decode(file_get_contents("php://input"), true);
    if(!$data || !isset($data['action'])) {
        throw new \RuntimeException('Invalid request data');
    }

    $admin = new Admin();
    $response = [];
    
    switch($data['action']) {
        case 'getAccessLogsForUser':
            if(empty($data['username'])) {
                throw new \InvalidArgumentException('Username required');
            }
            $response = $admin->getAccessLogsForUser($data['username']);
            break;
            
        case 'getAccessLogs':
            $response = $admin->getAccessLogs();
            break;
            
        default:
            throw new \RuntimeException('Invalid action');
    }
    
} catch (\Exception $e) {
    $response = [
        'success' => false,
        'message' => $e->getMessage()
    ];
}

echo json_encode($response);