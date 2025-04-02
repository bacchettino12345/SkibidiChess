<?php
    require_once '../Backend/SessionChecker.php';
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
    <title>Document</title>
    <link rel="stylesheet" href="../Style/output.css">
</head>

<body class="flex flex-row bg-[#302E2B]" >
    
    <div id="ChessPanel" class="ml-[10vw] mt-[1vw]">

        <div id="PlayerInfo" class="flex flex-row  mb-[0.1vw]">

            <img src="../Assets/Images/ImgPlaceholder.jpg" id="PlayerImg" class="w-[2vw] h-[2vw] rounded-[0.2vw]">

            <div id="nameInfo" class="ml-[0.5vw]">
                <h2 id="PlayerName" class="text-neutral-50 font-bold">Player Name</h2>
                <h2 id="MaterialCount" >ciao</h2>
            </div>

        </div>

        <canvas id="ChessBoard" class="rounded-[0.3vw]"></canvas>

        <div id="PlayerInfo" class="flex flex-row mt-[0.5vw]">

            <img src="../Assets/Images/ImgPlaceholder.jpg" id="PlayerImg" class="w-[2vw] h-[2vw] rounded-[0.2vw]">

            <div id="nameInfo" class="ml-[0.5vw]">
                <h2 id="PlayerName" class="text-neutral-50 font-bold">Player Name</h2>
                <h2 id="MaterialCount" >ciao</h2>
            </div>

        </div>
    </div>

    <div id="GameInfo" class="ml-[5vw] mt-[2vw] w-[10vw]">
        <h1>Gioca coi bot</h1>

        <div id="Bots" class="flex flex-row  h-[5vw] gap-[0.5vw]">
            <button><img src="../Assets/Images/ImgPlaceholder.jpg" class="rounded-[0.2vw]" ></button>
            <button><img src="../Assets/Images/ImgPlaceholder.jpg" class="rounded-[0.2vw]" ></button>
            <button><img src="../Assets/Images/ImgPlaceholder.jpg" class="rounded-[0.2vw]" ></button>
            <button><img src="../Assets/Images/ImgPlaceholder.jpg" class="rounded-[0.2vw]" ></button>
        </div>

        <button id="Suggestion" class="GreenBtn">Suggestion</button>
    </div>

    <script type="module" src="../src/js/game/Main2.js"></script>

    
</body>
</html>