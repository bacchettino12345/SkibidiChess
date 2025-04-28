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
    <div id="Home" class="flex items-center min-h-screen flex-col justify-center px-4">
        <img src="../Assets/Images/Logo.png" class="w-full max-w-[280px] md:max-w-[400px] mb-6 md:mb-4 drop-shadow-lg md:drop-shadow-[0_77px_35px_rgba(0,0,0,0.25)]">
        
        <div id="UserInfo" class="text-white text-lg md:text-2xl mb-6 md:mb-8">
            Logged in as: <span id="username"></span>
        </div>
        
        <div id="GameMod" class="flex flex-col w-full max-w-[280px] md:max-w-[400px]">
            <a href="singleplayersettings.html" class="w-full">
                <button id="Button" class="GreenBtn w-full h-12 md:h-14 text-base md:text-lg mb-4 md:mb-6">Singleplayer</button>
            </a>
            
            <a href="multiplayer.php" class="w-full">
                <button id="Button" class="GreenBtn w-full h-12 md:h-14 text-base md:text-lg mb-4 md:mb-6">Multiplayer</button>
            </a>
            
            <a href="./admin" class="w-full">
                <button id="adminButton" class="GreenBtn w-full h-12 md:h-14 text-base md:text-lg mb-4 md:mb-6" style="display: none;">Administration</button>
            </a>
            
            <button id="logoutButton" class="RedBtn w-full h-12 md:h-14 text-base md:text-lg mb-4 md:mb-6" style="display: none;" onclick="logoutHandler()">Logout</button>
            
            <a href="./login.html" class="w-full">
                <button id="loginButton" class="GreenBtn w-full h-12 md:h-14 text-base md:text-lg" style="display: none;">Login</button>
            </a>
        </div>
        
        <small class="text-gray-400 mb-0.5 mt-8 md:mt-10 text-xs md:text-sm text-center">SkibidiChess PREVIEW - SERVICE INFO: Mannaggia la madosca</small>
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