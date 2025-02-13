<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../Style/output.css">
    
</head>
<body class="bg-[#302E2B]">
    <div id="Home" class="flex justify-center items-center min-h-screen flex-col">
            <img src="../Assets/Images/Logo.png" class="w-[40vw] mb-[1vw] mt-[2vw] drop-shadow-[0_77px_35px_rgba(0,0,0,0.25)]">
            <div id="UserInfo" class="text-white text-2xl mb-[5vh]">
                Logged in as: <span id="username"></span>
            </div>
            <div id="GameMod" class="flex  flex-col">
                <a href="singleplayer.html"><button id="Button" class="GreenBtn w-[20vw] h-[6vh] text-[1vw]">Singleplayer</button></a>
                <a href="multiplayer.php"><button id="Button" class="GreenBtn w-[20vw] h-[6vh] mt-[3vw] text-[1vw]">Multiplayer</button></a>
                <a href="./admin"><button id="adminButton" class="GreenBtn w-[20vw] h-[6vh] mt-[3vw] text-[1vw]" style="display: none;">Administration</button></a>
                <a href="../Backend/auth/php/logout.php"><button id="logoutButton" class="RedBtn w-[20vw] h-[6vh] text-[1vw] mt-[3vw]" style="display: none;">Logout</button></a>
                <a href="./login.html"><button id="loginButton" class="GreenBtn w-[20vw] h-[6vh] mt-[3vw] text-[1vw]" style="display: none;">Login</button></a>
            </div>
        </div>
        <script>
            function enableAdmin()
            {
                document.getElementById("adminButton").style.display = 'block';
            }
            function enableLogout()
            {
                document.getElementById("logoutButton").style.display = 'block';
            }

            function enableLogin()
            {
                document.getElementById("loginButton").style.display = 'block';
            }
        </script>
            <?php
                if(session_status() == PHP_SESSION_NONE)
                {
                    session_start();
                }
                if(!isset($_SESSION['user']))
                {
                    $user = "Guest";
                    $admin = false;
                } else
                {
                    if(isset($_SESSION['admin'])){
                        $admin = true;
                    }
                    else
                        $admin = false;
                    $user = $_SESSION['user'];
                }
            ?>
        <script>
            let user = "<?php echo $user; ?>";
            let admin = "<?php echo $admin; ?>";
            if(user != "Guest")
            {
                enableLogout();
            }
            else
            {
                enableLogin();
            }
            if(admin === "1")
            {
                enableAdmin();
            }
            document.getElementById("username").innerText = user;
        </script>
</body>
</html>