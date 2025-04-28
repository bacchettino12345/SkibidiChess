<?php
require_once '../../Backend/SessionChecker.php';

use Skibidi\SessionChecker;

$sessionChecker = new SessionChecker();
if (!$sessionChecker->checkSession())
    header('Location: ../login.html');
else if (!$sessionChecker->checkAdmin())
    header('Location: not_authorized.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkibidiChess :: Administration</title>
    <link rel="shortcut icon" href="../../Assets/Images/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="../../Style/output.css">
</head>

<body class="bg-[#302E2B] overflow-hidden">
    <script>
        function isMobileDevice() {
            return window.innerWidth <= 768; // soglia comune per dispositivi mobili
        }

        if(isMobileDevice())
            alert("The admin panel is not designed for mobile devices and may appear distorted. Please use a desktop device for the best experience.");

    </script>
    <div class="flex items-center min-h-screen flex-col justify-center px-4">
        <div class="flex flex-col md:flex-row justify-center items-center gap-4 mb-6 md:mb-8">
            <img src="../../Assets/Images/Logo.png" alt="logo" class="w-full max-w-[280px] md:max-w-[400px] drop-shadow-lg md:drop-shadow-[0_77px_35px_rgba(0,0,0,0.25)]">
            <hr class="hidden md:block bg-white h-12 w-0.5">
            <p class="text-white text-lg md:text-2xl"><i>Administration Panel</i></p>
        </div>

        <div class="flex flex-col w-full max-w-[280px] md:max-w-[400px]">
            <a href="./tokens.php" class="w-full">
                <button id="Button" class="GreenBtn w-full h-12 md:h-14 text-base md:text-lg mb-4 md:mb-6">Game Token Manager</button>
            </a>

            <a href="./users.php" class="w-full">
                <button id="Button" class="GreenBtn w-full h-12 md:h-14 text-base md:text-lg mb-4 md:mb-6">User Manager</button>
            </a>

            <a href="./access_logs.php" class="w-full">
                <button id="Button" class="GreenBtn w-full h-12 md:h-14 text-base md:text-lg mb-4 md:mb-6">Access Logs</button>
            </a>

            <a href="../index.php" class="w-full">
                <button id="Button" class="RedBtn w-full h-12 md:h-14 text-base md:text-lg mb-4 md:mb-6">Back to Menu</button>
            </a>
        </div>
    </div>
</body>

</html>