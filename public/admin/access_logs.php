<?php
    require './check_login_admin.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="widtd=device-widtd, initial-scale=1.0">
    <title>SkibidiChess :: Administration</title>
    <link rel="stylesheet" href="../../Style/output.css">
</head>
<body class="bg-[#302E2B] w-[70%] mx-auto">
    <div class="flex justify-center items-center mt-[2vh] gap-4">
        <img src="../../Assets/Images/Logo.png" alt="logo" class="w-[225px]">
        <hr class="bg-white h-[50px] w-[2px]">
        <p class="text-white text-3xl"><i>Administration Panel</i></p>        
    </div>
    <a href="./" class="text-white"><i><< Back to Index</i></a>
    <div id="last_logins" class="text-white w-full mx-auto mt-[2vh]">
            <p class="text-white text-3xl">Last Logins</p>
            <div class="mt-[2vh] p-[20px] bg-[#1c1a19] rounded-[10px] w-full flex items-center gap-2">
                <label class="text-white">Enter User:</label>
                <input class="bg-[#22201e] px-2 py-1 outline-none text-white rounded-lg border-2 transition-colors duration-100 border-solid focus:border-[#739552] border-[#1c1a19]" 
                    placeholder="Username" id="getUserSearchUsername" type="text">
                <button id="getUserSearchBtn" class="GreenBtn py-1 mb-[0.625vh]" onclick="showUsers()">Search</button>
                <button id="clearResultsBtn" class="RedBtn py-1 mb-[0.625vh]" onclick="clearUsers()">Clear Results</button>
            </div>
            <div class="mt-[2vh] p-[20px] bg-[#1c1a19] rounded-[10px] w-full">
                <table class="w-full" id="loginsTable">
                    <tr>
                        <td>ID</td>
                        <td>User</td>
                        <td>IP</td>
                        <td>Time</td>
                    </tr>
                </table>
            </div>
        </div>
</body>
</html>
</html>