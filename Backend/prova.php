<?php

require_once '../vendor/autoload.php';
use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Device\DeviceParserAbstract;

// Inizializzazione
$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
$dd = new DeviceDetector($userAgent);

$dd->parse();



echo "User Agent: " . htmlspecialchars($userAgent) . "<br>";

if ($dd->isBot()) {
    echo "Questo è un bot: " . $dd->getBot()['name'] . "<br>";
} else {
    echo "Client: " . $dd->getClient('name') . " " . $dd->getClient('version') . "<br>";
    echo "Sistema Operativo: " . $dd->getOs('name') . " " . $dd->getOs('version') . "<br>";
    echo "Tipo di dispositivo: " . $dd->getDeviceName() . "<br>";
    echo "Marca: " . $dd->getBrandName() . "<br>";
    echo "Modello: " . $dd->getModel() . "<br>";
}
?>