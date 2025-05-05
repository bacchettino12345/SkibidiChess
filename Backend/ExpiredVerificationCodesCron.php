<?php

namespace Skibidi;
require_once __DIR__ . '/Database.php';

use PDOException;
$Database = new Database();
$conn = $Database->getConnection();


try {
    
    $sql = "DELETE FROM verification_codes WHERE expires < NOW()";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    echo ">> Expired Codes Deleted"; 
    
} catch (PDOException $e) {
    echo ">> Errorr: " . $e->getMessage();
}


?>