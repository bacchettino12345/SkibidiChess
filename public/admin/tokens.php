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
        <p class="text-white text-2xl"><i>Administration Panel</i></p>        
    </div>
    <a href="./" class="text-white"><i><< Back to Index</i></a>
    <div id="active_tokens" class="text-white  mx-auto mt-[2vh] mb-5">
        <p class="text-white text-3xl">Active Tokens</p>
        <div class="mt-[2vh] p-[20px] bg-[#1c1a19] rounded-[10px] w-full">
            <table class="w-full">
                <tr>
                    <td>Token</td>
                    <td>Date Generation</td>
                    <td>LMx</td>
                    <td>LMy</td>
                    <td>FEN</td>
                </tr>
            </table>
        </div>
    </div>
    <div id="users" class="text-white w-full mx-auto mt-[2vh]">
    <p class="text-white text-3xl">Destroy Token</p>
        <div class="mt-[2vh] p-[20px] bg-[#1c1a19] rounded-[10px] w-full flex items-center gap-2">
            <label for="username" class="text-white">Enter Token to destroy: </label>
            <input
                class="bg-[#22201e] px-2 py-1 outline-none text-white rounded-lg border-2 transition-colors duration-100 border-solid focus:border-[#b92828] border-[#1c1a19]"
                name="user"
                placeholder="Token"
                id="username"
                type="number">
            <button id="destroyToken" class="RedBtn py-1 mb-[0.625vh]">Destroy</button>
        </div>
    </div>
    <div id="users" class="text-white w-full mx-auto mt-[2vh]">
    <p class="text-white text-3xl">Change FEN</p>
        <div class="mt-[2vh] p-[20px] bg-[#1c1a19] rounded-[10px] w-full flex items-center gap-2">
            <label for="username" class="text-white">Enter Token: </label>
            <input
                class="bg-[#22201e] px-2 py-1 outline-none text-white rounded-lg border-2 transition-colors duration-100 border-solid focus:border-[#b92828] border-[#1c1a19]"
                name="user"
                placeholder="Token"
                id="username"
                type="number">
                <label for="username" class="text-white">Enter New FEN: </label>
            <input
                class="bg-[#22201e] px-2 py-1 outline-none text-white rounded-lg border-2 transition-colors duration-100 border-solid focus:border-[#b92828] border-[#1c1a19]"
                name="user"
                placeholder="FEN"
                id="username"
                type="text">
            <button id="destroyToken" class="RedBtn py-1 mb-[0.625vh]">Change</button>
        </div>
    </div>
</body>
</html>
</html>