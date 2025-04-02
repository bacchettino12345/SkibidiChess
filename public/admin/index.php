<?php
    require_once '../../Backend/SessionChecker.php';
    use Skibidi\SessionChecker;
    $sessionChecker = new SessionChecker();

    if(!$sessionChecker->checkSession())
        header('Location: login.html');
    else if(!$sessionChecker->checkAdmin())
        header('Location: not_authorized.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkibidiChess :: Administration</title>
    <link rel="stylesheet" href="../../Style/output.css">

</head>
<body class="bg-[#302E2B] w-[70%] mx-auto">
    <div class="flex justify-center items-center min-h-screen flex-col">
        <div class="flex justify-center items-center gap-4 mb-5.5">
            <img src="../../Assets/Images/Logo.png" alt="logo" class="w-[25%]">
            <hr class="bg-white h-[50px] w-[2px]">
            <p class="text-white text-3xl"><i>Administration Panel</i></p>
                    
        </div>
    
    <a href="./tokens.php"><button id="Button" class="GreenBtn w-[20vw] h-[6vh] text-[1vw] mb-10">Game Token Manager</button></a>
    <a href="./users.php"><button id="Button" class="GreenBtn w-[20vw] h-[6vh] text-[1vw] mb-10">User Manager</button></a>
    <a href="./access_logs.php"><button id="Button" class="GreenBtn w-[20vw] h-[6vh] text-[1vw] mb-10">Access Logs</button></a>
    <a href="../index.php"><button id="Button" class="RedBtn w-[20vw] h-[6vh] text-[1vw] mb-10">Back to Menu</button></a>
    </div>

</body>
</html>