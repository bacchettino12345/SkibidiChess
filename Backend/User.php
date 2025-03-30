<?php
require 'Database.php';
require 'EmailSender.php'; 

class User
{
    private $conn;
    private $emailSender;

    public function __construct()
    {
        $Database = new Database();
        $this->conn = $Database->getConnection();
        $EmailSender = new EmailSender();
        $this->emailSender = $EmailSender;
    }

    private function isUsernameTaken($username)
    {
        $sql = "SELECT COUNT(*) FROM accounts WHERE username = :username";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }


    public function createUser($firstname, $lastname, $username, $email, $password)
    {
        if(session_status() == PHP_SESSION_NONE)
        {
            session_start();
        }
        session_destroy();
        $_SESSION = [];

        $password = password_hash($password, PASSWORD_BCRYPT);

        if (empty($username) || empty($firstname) || empty($lastname) || empty($email) || empty($password)) {
            echo json_encode(['success' => false, 'message' => 'All fields are required.']);
            exit();
        } 
        else if (!preg_match("/^[a-zA-Z0-9_]*$/", $username)) {
            echo json_encode(['success' => false , 'message' => 'Invalid username. Only letters, numbers, and underscores are allowed.']);
            exit();
        } 
        else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Invalid email format.']);
            exit();
        }
        else if ($this->isUsernameTaken($username)) {
            echo json_encode(['success' => false, 'message' => 'Username already taken.']);
            exit();
        }


        try
        {
            $sql = "INSERT INTO accounts (username, firstname, lastname, email, password) VALUES (:username, :firstname, :lastname, :email, :password)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':firstname', $firstname);
            $stmt->bindParam(':lastname', $lastname);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            $stmt->execute(); 

            echo json_encode(['success' => true]);
        } catch (Exception $e)
        {
            echo json_encode(['success' => false, 'message' => 'Error creating user: ' . $e->getMessage()]); 
        }
    }



    private function getClientIP() {
        // TRANSFORMA IN GET DEVICE INFO 
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    private function registerLogin($id)
    {
        // AGGIUNGI LOGIN AL REGISTRO
        $ip = $this->getClientIP();
        $time = date('Y-m-d H:i:s');
        $sql = "INSERT INTO access_logs (user_id, ip, time) VALUES (:id, :ip, :time)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':ip', $ip);
        $stmt->bindParam(':time', $time);
        $stmt->execute();
    }
    
    public function loginUser($username, $password)
    {
        if(session_status() == PHP_SESSION_NONE)
        {
            session_start();
        }

        session_regenerate_id(true);

        /*
        session_destroy();
        $_SESSION = [];
        session_start();
        */

        if (empty($username) || empty($password)) {
            echo json_encode(['success' => false, 'message' => 'All fields are required.']);
            exit();
        }

        try {
            $sql = "SELECT * FROM accounts WHERE username = :username";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'firstname' => $user['firstname'],
                    'lastname' => $user['lastname'],
                    'email' => $user['email']
                ];
                
                $this->emailSender->sendTestMail($user['email']);

                $this->registerLogin($user['id']);

                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid username or password.']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error logging in: ' . $e->getMessage()]);
        }
    }

    public function logoutUser()
    {
        if(session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        $_SESSION = [];

    }

    public function checkIfAdmin($username)
    {
        try
        {
            $sql = "SELECT * FROM accounts WHERE username = :username AND admin = 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            echo json_encode(['success' => true, 'isAdmin' => $stmt->fetch(PDO::FETCH_ASSOC) !== false]);


        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error checking admin status: ' . $e->getMessage()]);
        }

    }


}


$user = new User();

header('Content-Type: application/json');
$data = json_decode(file_get_contents("php://input"), true);
if ($data) {
    $action = $data['action'];
    switch ($action) {
        case 'createUser':
            $user->createUser($data['firstname'], $data['lastname'], $data['username'], $data['email'], $data['password']);
            break;
        case 'loginUser':
            $user->loginUser($data['username'], $data['password']);
            break;
        case 'logoutUser':
            $user->logoutUser();
            break;
        case 'checkIfAdmin':
            $user->checkIfAdmin($data['username']);
            break;
        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No data received.']);
}


?>