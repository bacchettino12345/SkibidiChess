
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skibidichess</title>
    <link rel="shortcut icon" href="../Assets/Images/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="../Style/output.css">
</head>
<body class="bg-[#302E2B] overflow-hidden">
    <script>
        async function logoutHandler()
        {
            const response = await fetch('../Backend/User.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    action: 'logoutUser'
                })
            });
            window.location.reload();        
        }
    </script>
    <div id="Home" class="flex items-center min-h-screen flex-col justify-center">
        <img src="../Assets/Images/Logo.png" class="w-[35vw] mb-[1vw] drop-shadow-[0_77px_35px_rgba(0,0,0,0.25)]">
        <div id="UserInfo" class="text-white text-2xl mb-[5vh]">
            Logged in as: <span id="username"></span>
        </div>
        <div id="GameMod" class="flex flex-col">
            <a href="singleplayer.html"><button id="Button" class="GreenBtn w-[20vw] h-[6vh] text-[1vw]">Singleplayer</button></a>
            <a href="multiplayer.php"><button id="Button" class="GreenBtn w-[20vw] h-[6vh] mt-[3vw] text-[1vw]">Multiplayer</button></a>
            <a href="./admin"><button id="adminButton" class="GreenBtn w-[20vw] h-[6vh] mt-[3vw] text-[1vw]" style="display: none;">Administration</button></a>
            <button id="logoutButton" class="RedBtn w-[20vw] h-[6vh] text-[1vw] mt-[3vw]" style="display: none;" onclick="logoutHandler()">Logout</button>
            <a href="./login.html"><button id="loginButton" class="GreenBtn w-[20vw] h-[6vh] mt-[3vw] text-[1vw]" style="display: none;">Login</button></a>
        </div>
        <small class="text-gray-400 mb-0.5 mt-10">SkibidiChess PREVIEW - SERVICE INFO: Mannaggia la madosca<small>
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
            use Skibidi\SessionChecker;
            require '../Backend/SessionChecker.php';
            $sessionChecker = new SessionChecker();

            if($sessionChecker->checkSession())
            {
                $username = $_SESSION['user']['username'];
                $admin = $sessionChecker->checkAdmin();
            }
            else
            {
                $username = "Guest";
                $admin = 0;
            }
        ?>
        <script>
            let user = <?php echo json_encode($username); ?>;
            console.log(user);
            let admin = <?php echo json_encode($admin); ?>;
            console.log(admin);
            if(user != "Guest")
            {
                enableLogout();
            }
            else
            {
                enableLogin();
            }
            if(admin == true)
            {
                enableAdmin();
            }
            document.getElementById("username").innerText = user;

        </script>
</body>
</html>