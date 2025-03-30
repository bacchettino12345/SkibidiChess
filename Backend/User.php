<?php
require 'Database.php';
require 'EmailSender.php'; 

require_once '../vendor/autoload.php';
use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Device\DeviceParserAbstract;

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


    public function registrationInputValidator($username, $firstname, $lastname, $email, $password)
    {
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
    }



    public function createEmailVerifyCode($firstname, $lastname, $username, $email, $password)
    {
        $this->registrationInputValidator($username, $firstname, $lastname, $email, $password);

        try
        {
            // TOGLIERE COUNT E PREDERE IL CODICE E SPEDIRLO PER EMAIL
            $sql = "SELECT COUNT(*) FROM verification_codes WHERE email = :email";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            if($stmt->fetchColumn() > 0)
            {
                echo json_encode(['success' => true]);
            }
            else
            {

                $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

                $sql = "INSERT INTO verification_codes (code, email) VALUES (:code, :email)";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':code', $code);
                $stmt->execute();
                /// SPEDIRE CODICE PER EMAIL
                echo json_encode(['success' => true]);
            }

        } catch (Exception $e)
        {
            echo json_encode(['success' => false, 'error' => 'Internal Database erorr: ' . $e->getMessage()]);
        }
    }


    private function verifyEmailVerifyCode($email, $code)
    {
        try
        {
            $sql = "SELECT COUNT(*) FROM verification_codes WHERE email = :email AND code = :code";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':code', $code);
            $stmt->execute();
            if($stmt->fetchColumn() > 0)
                return true;
            else
                return false;
        } catch (Exception $e)
        {
            echo json_encode(['success' => false, 'message' => 'Errore durante la verifica del codice: ' . $e->getMessage()]);
            exit();
        }
    }


    public function createUser($firstname, $lastname, $username, $email, $password, $code)
    {
        if(session_status() == PHP_SESSION_NONE)
        {
            session_start();
        }
        session_destroy();
        $_SESSION = [];

        $password = password_hash($password, PASSWORD_BCRYPT);
    
        $this->registrationInputValidator($username, $firstname, $lastname, $email, $password);

        if($this->verifyEmailVerifyCode($email, $code))
        {
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
        else
        {
            echo json_encode(['success' => false, 'message' => 'Wrong verification code.']); 
        }

    }



    private function getAccessInfo() {
        
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $dd = new DeviceDetector($userAgent);
        $dd->parse();

        $time = date('Y-m-d H:i:s');
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        


        $brandName = $dd->getBrandName();
        $model = $dd->getModel();

        $device = 'N/A';
        if (!empty($brandName) || !empty($model)) {
            $device = trim(($brandName ?? '') . ' ' . ($model ?? ''));
        }

        $osName = $dd->getOs('name');
        $osVersion = $dd->getOs('version');

        $os = 'N/A';
        if (!empty($osName) || !empty($osVersion)) {
            $os = trim(($osName ?? '') . ' ' . ($osVersion ?? ''));
        }

        $clientName = $dd->getClient('name');
        $clientVersion = $dd->getClient('version');

        $client = 'N/A';
        if (!empty($clientName) || !empty($clientVersion)) {
            $client = trim(($clientName ?? '') . ' ' . ($clientVersion ?? ''));
        }



        $details = json_decode(file_get_contents("http://ip-api.com/json/{$ip}"));
        if ($details && $details->status === 'success')
            $info = ["ip" => $ip, "device" => $device, "os" => $os, "client" => $client, "country" => $details->country, "region" => $details->regionName, "city" => $details->city, "isp" => $details->isp, "as" => $details->as, "time" => $time];
        else
            $info = ["ip" => $ip, "device" => $device, "os" => $os, "client" => $client, "country" => "N/A", "region" => "N/A", "city" => "N/A", "isp" => "N/A", "as" => "N/A", "time" => $time];

        return $info;

    }

    private function registerLogin($id, $info)
    {
        try
        {
            $sql = "INSERT INTO access_logs (user_id, ip, device, os, client, country, region, city, isp, as_, time) VALUES (:id, :ip, :device, :os, :client, :country,:region, :city, :isp, :as, :time)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':ip', $info['ip']);
            $stmt->bindParam(':device', $info['device']);
            $stmt->bindParam(':os', $info['os']);
            $stmt->bindParam(':client', $info['client']);
            $stmt->bindParam(':country', $info['country']);
            $stmt->bindParam(':region', $info['region']);
            $stmt->bindParam(':city', $info['city']);
            $stmt->bindParam(':isp', $info['isp']);
            $stmt->bindParam(':as', $info['as']);
            $stmt->bindParam(':time', $info['time']);
            $stmt->execute();
        } catch (Exception $e)
        {
            echo json_encode(['success' => false, 'message' => 'Error writing logs on the Database: ' . $e->getMessage()]);
        }
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


        $info = $this->getAccessInfo();

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
                
                $this->emailSender->sendLoginMail($user['email'], $info);

                $this->registerLogin($user['id'], $info);

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
            $user->createUser($data['firstname'], $data['lastname'], $data['username'], $data['email'], $data['password'], $data['code']);
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
        case 'createEmailVerifyCode':
            $user->createEmailVerifyCode($data['firstname'], $data['lastname'], $data['username'], $data['email'], $data['password']);
            break;
        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No data received.']);
}


?>