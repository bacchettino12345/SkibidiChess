<?php
require 'Database.php';

class SessionChecker
{
    private $conn;

    public function __construct()
    {
        $Database = new Database();
        $this->conn = $Database->getConnection();
    }

    public function checkSession()
    {
        session_start();
        if(true)
        {
            try 
            {
                $session = $_SESSION['user'];
                $sql = "SELECT * FROM accounts WHERE username = :user AND id = :id AND email = :email";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(':user', $session['username']);
                $stmt->bindParam(':email', $session['email']);
                $stmt->bindParam(':id', $session['id']);
                $stmt->execute();
                $results = $stmt->fetch(PDO::FETCH_ASSOC);
                if($results)
                {
                    return true;
                }
                else
                {
                    $sql = "DELETE FROM accounts WHERE username = :user AND id = :id AND email = :email";
                    $stmt = $this->conn->prepare($sql);
                    $stmt->bindParam(':user', $session['username']);
                    $stmt->bindParam(':email', $session['email']);
                    $stmt->bindParam(':id', $session['id']);
                    $stmt->execute();
                    session_destroy();
                    $_SESSION = [];
                    return false;
                }
            } catch (Exception $e)
            {
                echo "ERRRRE";
                return false;
            }
        }
        else
        {
            return false;
        }
    }

    public function checkAdmin()
    {
        session_start();
        if(true)
        {
            try
            {   
                $session = $_SESSION['user'];
                $sql = "SELECT COUNT(*) FROM accounts WHERE username = :user AND id = :id AND email = :email";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(':user', $session['username']);
                $stmt->bindParam(':email', $session['email']);
                $stmt->bindParam(':id', $session['id']);
                $stmt->execute();
                return true;
                echo "<script>alert(true)</script>";
            }
            catch (Exception $e)
            {
                echo "<script>alert(false)</script>";

                return false;
            }
        }
    }


}



?>
