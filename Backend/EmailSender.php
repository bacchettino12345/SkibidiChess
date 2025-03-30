<?php
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailSender {

    public function sendLoginMail($to, $info) {
        $mail = new PHPMailer(true);

        try {

            $mail->isSMTP();
            $mail->Host       = 'smtps.aruba.it';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'noreply.skibidichess@riccardocinaglia.it';
            $mail->Password   = 'L!cmmcM(8W2v-P4{';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            $mail->setFrom('noreply.skibidichess@riccardocinaglia.it', 'noreply');
            $mail->addAddress($to);

            $mail->isHTML(true);
            $mail->Subject = 'Security Alert';
            $mail->Body    = '
                <!DOCTYPE html>
<html lang="it">

<head>
    <style type="text/css">
        /* Reset stili base */
        body,
        html {
            margin: 0;
            padding: 0;
            font-family: "Arial", sans-serif;
            line-height: 1.6;
            color: #ffffff !important;
            /* Aggiunto !important */
        }

        /* Stili contenitore principale */
        .email-container {
            text-align: center;
            margin: 0 auto;
            background-color: #3a3938;
            color: #ffffff !important;
            /* Aggiunto */
        }

        /* Header con logo */
        .header {
            padding: 30px 20px;
            text-align: center;
            background-color: #302e2b;
            border-bottom: 1px solid #474747;
            color: #ffffff !important;
            /* Aggiunto */
        }

        .logo {
            max-width: 200px;
            height: auto;
        }

        /* Contenuto principale */
        .content {
            padding: 30px 20px;
            color: #ffffff !important;
            /* Aggiunto */
        }

        h1 {
            color: #ffffff !important;
            font-size: 24px;
            margin-top: 0;
        }

        p {
            margin-bottom: 20px;
            font-size: 16px;
            color: #ffffff !important;
            /* Aggiunto */
        }

        a {
            color: #ffffff !important;
            /* Per i link */
        }

        .button {
            display: inline-block;
            padding: 12px 25px;
            background-color: #3498db;
            color: #ffffff !important;
            /* Cambiato da #414141 a #ffffff */
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            margin: 20px 0;
        }

        /* Footer */
        .footer {
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #ffffff !important;
            /* Cambiato da #777777 */
            background-color: #302e2b;
            border-top: 1px solid #474747;
        }

        /* Stili responsive */
        @media screen and (max-width: 480px) {
            .content {
                padding: 20px 15px;
            }

            h1 {
                font-size: 20px;
            }

            p {
                font-size: 14px;
            }

            .button {
                padding: 10px 20px;
            }
        }
    </style>
</head>

<body>
    <div class="email-container">
        <!-- Header con logo -->
        <div class="header">
            <img src="https://www.riccardocinaglia.it//Logo.png" alt="Logo" class="logo">
        </div>

        <!-- Contenuto email -->
        <div class="content">
            <h1 style="text-align: center">NEW ACCESS DETECTED</h1>
            <p>We have detected a new access to your SkibidiChess Account</p>
            <ul style="list-style: none; margin: 0; padding: 0;">
                <li style="list-style: none; margin: 0; padding: 0;"><b>Time: '. $info['time'] .'</b></li>
                <li style="list-style: none; margin: 0; padding: 0;"><b>IP: </b>'. $info['ip'] .'</li>
                <li style="list-style: none; margin: 0; padding: 0;"><b>Client: </b>'. $info['client'] .'</li>
                <li style="list-style: none; margin: 0; padding: 0;"><b>OS: </b>'. $info['os'] .'</li>
                <li style="list-style: none; margin: 0; padding: 0;"><b>Device: </b>'. $info['device'] .'</li>
                <li style="list-style: none; margin: 0; padding: 0;"><b>Country: </b>'. $info['country'] .'</li>
                <li style="list-style: none; margin: 0; padding: 0;"><b>Region: </b>'. $info['region'] .'</li>
                <li style="list-style: none; margin: 0; padding: 0;"><b>City: </b>'. $info['city'] .'</li>
                <li style="list-style: none; margin: 0; padding: 0;"><b>ISP: </b>'. $info['isp'] .'</li>
            </ul>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>© 2025 SkibidiChess - PREVIEW</p>
            <p>
                <a href="" style="color: #3498db !important; text-decoration: none;">Play Now</a> |
                <a href="" style="color: #3498db !important; text-decoration: none;">Privacy Policy</a> |
                <a href="" style="color: #3498db !important; text-decoration: none;">Contact Us</a>
            </p>
        </div>
    </div>
</body>

</html>
            ';
            $mail->AltBody = "Test Message from Skibidichess Systems";

            $mail->send();
            return true;
        } catch (Exception $e) {
            echo "Errore nell'invio email: {$mail->ErrorInfo}";
            return false;
        }
    }


    public function sendTestMail($to) {
        $mail = new PHPMailer(true);

        try {

            $mail->isSMTP();
            $mail->Host       = 'smtps.aruba.it';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'noreply.skibidichess@riccardocinaglia.it';
            $mail->Password   = 'L!cmmcM(8W2v-P4{';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            $mail->setFrom('noreply.skibidichess@riccardocinaglia.it', 'noreply');
            $mail->addAddress($to);

            $mail->isHTML(true);
            $mail->Subject = 'Test Message';
            $mail->Body    = '
                <!DOCTYPE html>
                <html lang="it">
                <head>
                    <style type="text/css">
                        /* Reset stili base */
                        body, html {
                            margin: 0;
                            padding: 0;
                            font-family: "Arial", sans-serif;
                            line-height: 1.6;
                            color: #ffffff !important; /* Aggiunto !important */
                        }
                        
                        /* Stili contenitore principale */
                        .email-container {
                            text-align: center;
                            margin: 0 auto;
                            background-color: #3a3938;
                            color: #ffffff !important; /* Aggiunto */
                        }
                        
                        /* Header con logo */
                        .header {
                            padding: 30px 20px;
                            text-align: center;
                            background-color: #302e2b;
                            border-bottom: 1px solid #474747;
                            color: #ffffff !important; /* Aggiunto */
                        }
                        
                        .logo {
                            max-width: 200px;
                            height: auto;
                        }
                        
                        /* Contenuto principale */
                        .content {
                            padding: 30px 20px;
                            color: #ffffff !important; /* Aggiunto */
                        }
                        
                        h1 {
                            color: #ffffff !important;
                            font-size: 24px;
                            margin-top: 0;
                        }
                        
                        p {
                            margin-bottom: 20px;
                            font-size: 16px;
                            color: #ffffff !important; /* Aggiunto */
                        }
                        
                        a {
                            color: #ffffff !important; /* Per i link */
                        }
                        
                        .button {
                            display: inline-block;
                            padding: 12px 25px;
                            background-color: #3498db;
                            color: #ffffff !important; /* Cambiato da #414141 a #ffffff */
                            text-decoration: none;
                            border-radius: 4px;
                            font-weight: bold;
                            margin: 20px 0;
                        }
                        
                        /* Footer */
                        .footer {
                            padding: 20px;
                            text-align: center;
                            font-size: 12px;
                            color: #ffffff !important; /* Cambiato da #777777 */
                            background-color: #302e2b;
                            border-top: 1px solid #474747;
                        }
                        
                        /* Stili responsive */
                        @media screen and (max-width: 480px) {
                            .content {
                                padding: 20px 15px;
                            }
                            
                            h1 {
                                font-size: 20px;
                            }
                            
                            p {
                                font-size: 14px;
                            }
                            
                            .button {
                                padding: 10px 20px;
                            }
                        }
                    </style>
                </head>
                <body>
                    <div class="email-container">
                        <!-- Header con logo -->
                        <div class="header">
                            <img src="https://www.riccardocinaglia.it//Logo.png" alt="Logo" class="logo">
                        </div>
                        
                        <!-- Contenuto email -->
                        <div class="content">
                            <h1>Messaggio di Test</h1>
                            
                            <p>TEST INVIO MAIL AUTOMATICO</p>
                        </div>
                        
                        <!-- Footer -->
                        <div class="footer">
                            <p>© 2025 SkibidiChess - PREVIEW</p>
                            <p>
                                <a href="" style="color: #3498db !important; text-decoration: none;">Play Now</a> | 
                                <a href="" style="color: #3498db !important; text-decoration: none;">Privacy Policy</a> | 
                                <a href="" style="color: #3498db !important; text-decoration: none;">Contact Us</a>
                            </p>
                        </div>
                    </div>
                </body>
                </html>
            ';
            $mail->AltBody = "Test Message from Skibidichess Systems";

            $mail->send();
            return true;
        } catch (Exception $e) {
            echo "Errore nell'invio email: {$mail->ErrorInfo}";
            return false;
        }
    }
}
?>