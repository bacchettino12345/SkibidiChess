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

    private function checkUserExistance($username)
    {
        $sql = "SELECT COUNT(*) FROM accounts WHERE username = :username";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }


    public function userSearch($search)
    {
        if (empty($search) || strlen($search) < 3) {
            echo json_encode(['success' => false, 'message' => 'Username search term must be at least 3 characters']);
            return;
        }   

        try {
            $sql = "SELECT id, username, firstname, lastname, email, active, admin, pts
                    FROM accounts a
                    WHERE username LIKE :query
                    OR firstname LIKE :query
                    OR lastname LIKE :query
                    OR email LIKE :query
                    LIMIT 500";
            $stmt = $this->conn->prepare($sql);
            $searchTerm = "%$search%";
            $stmt->bindParam(':query', $searchTerm, PDO::PARAM_STR);
            $stmt->execute();

            echo json_encode([
                'success' => true,
                'users' => $stmt->fetchAll(PDO::FETCH_ASSOC)
            ]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Database error: ']);
        }
    }

    public function getAccessLogsForUser($username)
    {
        if (empty($username) || strlen($username) < 3) {
            echo json_encode(['success' => false, 'message' => 'Username search term must be at least 3 characters']);
            return;
        }

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

    public function setPoints($username, $points)
    {
        if (empty($username) || strlen($username) < 3) {
            echo json_encode(['success' => false, 'message' => 'Username search term must be at least 3 characters']);
            return;
        }
        if (!$this->checkUserExistance($username)) {
            echo json_encode(['success' => false, 'message' => 'User does not exists on the DB']);
            return;
        }

        try {
            $sql = "update accounts a set a.pts = :pts where a.username = :username";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':pts', $points);
            $stmt->execute();

            echo json_encode([
                'success' => true,
                'message' => 'Operation Success!'
            ]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Database error']);
        }
    }

    public function setAdmin($username, $perm)
    {
        if (empty($username) || strlen($username) < 3) {
            echo json_encode(['success' => false, 'message' => 'Username search term must be at least 3 characters']);
            return;
        }
        if (!$this->checkUserExistance($username)) {
            echo json_encode(['success' => false, 'message' => 'User does not exists on the DB']);
            return;
        }

        try {
            $sql = "update accounts a set a.admin = :perm where a.username = :username;";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':perm', $perm);
            $stmt->execute();

            echo json_encode([
                'success' => true,
                'message' => 'Operation Success!'
            ]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Database error']);
        }
    }

    public function setActive($username, $act)
    {
        if (empty($username) || strlen($username) < 3) {
            echo json_encode(['success' => false, 'message' => 'Username search term must be at least 3 characters']);
            return;
        }
        if (!$this->checkUserExistance($username)) {
            echo json_encode(['success' => false, 'message' => 'User does not exists on the DB']);
            return;
        }

        try {
            $sql = "update accounts a set a.active = :active where a.username = :username";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':active', $act);
            $stmt->execute();

            echo json_encode([
                'success' => true,
                'message' => 'Operation Success!'
            ]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Database error']);
        }
    }

    public function changeUsername($username, $newUsername)
    {
        if (empty($username) || strlen($username) < 3) {
            echo json_encode(['success' => false, 'message' => 'Username search term must be at least 3 characters']);
            return;
        }
        if (!$this->checkUserExistance($username)) {
            echo json_encode(['success' => false, 'message' => 'User does not exists on the DB']);
            return;
        }

        try {
            $sql = "";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':new', $newUsername);
            $stmt->execute();

            echo json_encode([
                'success' => true,
                'message' => 'Operation Success!'
            ]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Database error']);
        }
    }

    public function changePassword($username, $newPassword)
    {
        if (empty($username) || strlen($username) < 3) {
            echo json_encode(['success' => false, 'message' => 'Username search term must be at least 3 characters']);
            return;
        }
        if (!$this->checkUserExistance($username)) {
            echo json_encode(['success' => false, 'message' => 'User does not exists on the DB']);
            return;
        }

        $newPassword = password_hash($newPassword, PASSWORD_BCRYPT);

        try {
            $sql = "";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':new', $newPassword);
            $stmt->execute();

            echo json_encode([
                'success' => true,
                'message' => 'Operation Success!'
            ]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Database error']);
        }
    }

    public function deleteUser($username)
    {
        if (empty($username) || strlen($username) < 3) {
            echo json_encode(['success' => false, 'message' => 'Username search term must be at least 3 characters']);
            return;
        }
        if (!$this->checkUserExistance($username)) {
            echo json_encode(['success' => false, 'message' => 'User does not exists on the DB']);
            return;
        }

        try {
            $sql = "delete from accounts where username = :username";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $sql = "delete from accounts where username = :username";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            echo json_encode([
                'success' => true,
                'message' => 'Operation Success!'
            ]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Database error']);
        }
    }
}




header('Content-Type: application/json');
$data = json_decode(file_get_contents("php://input"), true);

$sessionChecker = new SessionChecker();

if ($sessionChecker->checkSession())
    if ($sessionChecker->checkAdmin()) {
        $admin = new Admin();
        switch ($data['action']) {
            case 'getAccessLogsForUser':
                $admin->getAccessLogsForUser($data['username']);
                break;
            case 'getAccessLogs':
                $admin->getAccessLogs();
                break;
            case 'userSearch':
                $admin->userSearch($data['username']);
                break;
            case 'setPoints':
                $admin->setPoints($data['username'], $data['points']);
                break;
            case 'setAdmin':
                $admin->setAdmin($data['username'], $data['admin']);
                break;
            case 'setActive':
                $admin->setActive($data['username'], $data['active']);
                break;
            case 'deleteUser':
                $admin->deleteUser($data['username']);
                break;
            default:
                echo json_encode(['success' => false, 'message' => 'Invalid action.']);
                break;
        }
    } else
        echo json_encode(['success' => false, 'message' => 'Auth error.']);
