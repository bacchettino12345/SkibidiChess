<?php
require 'EmailSender.php'; 

$recipient_email = "daddyalberty@gmail.com";

$emailSender = new EmailSender();

if ($emailSender->sendTestMail($recipient_email)) {
    echo "Email inviata con successo a $recipient_email";
} else {
    echo "Si è verificato un errore durante l'invio dell'email";
}
?>