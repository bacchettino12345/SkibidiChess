<?php
    
    require '../../db_connection.php';
    header('Content-Type: application/json');
    $data = json_decode(file_get_contents("php://input"), true);
    session_start();

    if ($data) {
        $user = $data['username'];
        $name = $data['name'];
        $surname = $data['surname'];
        $mail = $data['email'];
        $psswd = hash("sha256", $data['password']);
        
    }
    session_destroy();
    $_SESSION = [];

    try
    {
        /* $sql = "";
        $stmt = $conn->prepare($sql);
        $stmt->execute(); */
        header('Location: ../../../public/accountCreatedRedirect.html');
    } catch (Exception $e)
    {
        error_log("Errore esecuzione query di registrazione (inserimento dati) nel db: " . $e->getMessage());
        // Redirect Errore interno da aggiungere
        // header('Location: ../../../public/accountCreatedRedirect.html'); 
    }


    
?>
