<?php
require 'EmailSender.php'; 

$recipient_email = "mail@mail.it";

$emailSender = new EmailSender();

if ($emailSender->sendVerificationEmail($recipient_email)) {
    echo "Email inviata con successo a $recipient_email";
} else {
    echo "Si è verificato un errore durante l'invio dell'email";
}
?>