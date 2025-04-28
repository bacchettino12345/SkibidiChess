<?php
namespace Skibidi;

require_once __DIR__ . '/Database.php';

use Skibidi\Database;
use PDO;
use Exception;

class SessionChecker
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function checkSession()
    {
        if(session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if(isset($_SESSION['user'])) {
            try {
                $session = $_SESSION['user'];
                $sql = "SELECT * FROM accounts WHERE username = :user AND id = :id AND email = :email AND active = 1";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(':user', $session['username'], PDO::PARAM_STR);
                $stmt->bindParam(':email', $session['email'], PDO::PARAM_STR);
                $stmt->bindParam(':id', $session['id'], PDO::PARAM_INT);
                $stmt->execute();
                
                return (bool)$stmt->fetch(PDO::FETCH_ASSOC);
                
            } catch (Exception $e) {
                error_log("Session check error: " . $e->getMessage());
                return false;
            }
        }
        return false;
    }

    public function checkAdmin()
    {
        if(!$this->checkSession()) {
            return false;
        }

        try {
            $session = $_SESSION['user'];
            $sql = "SELECT admin FROM accounts WHERE username = :user AND id = :id AND email = :email";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':user', $session['username'], PDO::PARAM_STR);
            $stmt->bindParam(':email', $session['email'], PDO::PARAM_STR);
            $stmt->bindParam(':id', $session['id'], PDO::PARAM_INT);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result && $result['admin'] == 1;
            
        } catch (Exception $e) {
            error_log("Admin check error: " . $e->getMessage());
            return false;
        }
    }
}