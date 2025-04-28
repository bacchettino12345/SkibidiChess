<?php
    require_once '../Backend/SessionChecker.php';
    use Skibidi\SessionChecker;
    $sessionChecker = new SessionChecker();
    if(!$sessionChecker->checkSession())
        header('Location: login.html');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkibidiChess :: Not Available</title>
    <link rel="shortcut icon" href="../Assets/Images/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="../Style/output.css">
</head>
<body class="bg-[#302E2B] overflow-hidden">
    <div class="flex items-center min-h-screen flex-col justify-center px-4">
        <img src="../Assets/Images/Logo.png" class="w-full max-w-[280px] md:max-w-[400px] mb-6 md:mb-8 drop-shadow-lg md:drop-shadow-[0_77px_35px_rgba(0,0,0,0.25)]">
        
        <div id="UserInfo" class="text-white text-lg md:text-2xl mb-6 md:mb-8 text-center">
            Section not Available.
        </div>
        
        <a href="index.php" class="italic text-white text-base md:text-lg">&lt;&lt; Back to Homepage</a>
        
        <small class="text-gray-400 mb-0.5 mt-8 md:mt-10 text-xs md:text-sm text-center">SkibidiChess PREVIEW</small>
    </div>
</body>
</html>