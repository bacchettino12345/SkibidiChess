<?php
require '../../db_connection.php';
require '../../../public/admin/check_login_admin.php';
// Imposta l'header per indicare che la risposta è in formato JSON
header('Content-Type: application/json');

$sql = "SELECT * FROM tokens";
$result = $conn->query($sql);

// Array per accumulare i dati
$data = [];

if ($result) {
    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        // Aggiungiamo ogni riga al nostro array
        $data[] = $row;
    }
}

// Converte l'array in JSON e lo restituisce
echo json_encode($data);
?>
