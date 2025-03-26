<?php
    if(session_status()  == PHP_SESSION_NONE)
    {
        session_start();
    }
    if(isset($_SESSION['user'])){
        if(isset($_SESSION['admin'])){
            header("Location: ./index.php");
        }   
    } else
    {
        header("Location: ../login.html");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied</title>
    <link rel="stylesheet" href="../../Style/output.css">
    
</head>
<body class="bg-[#302E2B] flex justify-center items-center min-h-screen flex-col">
    <img src="../../Assets/Images/Logo.png" class="w-[40vw] mb-[4vw] mt-[1vw] drop-shadow-[0_77px_35px_rgba(0,0,0,0.25)]">
    <div id="UserInfo" class="text-white text-5xl mb-[5vh]">
        Not Authorized!<span id="username"></span>
    </div>
    <a href="../index.php" class="italic text-white"><< Back to Homepage</a>
</body>
</html>