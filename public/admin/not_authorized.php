<?php
    if(session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if(isset($_SESSION['user'])) {
        if(isset($_SESSION['admin'])) {
            header("Location: ./index.php");
        }   
    } else {
        header("Location: ../login.html");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied</title>
    <link rel="shortcut icon" href="../../Assets/Images/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="../../Style/output.css">
</head>
<body class="bg-[#302E2B] flex justify-center items-center min-h-screen flex-col p-4">
    <img src="../../Assets/Images/Logo.png" class="w-full max-w-md mb-8 mt-4 drop-shadow-lg">
    <div id="UserInfo" class="text-white text-2xl md:text-4xl lg:text-5xl mb-8 text-center">
        Not Authorized!<span id="username"></span>
    </div>
    <a href="../index.php" class="italic text-white text-lg hover:underline"><< Back to Homepage</a>
</body>
</html>