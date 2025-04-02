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
    <meta name="viewport" content="widtd=device-widtd, initial-scale=1.0">
    <title>SkibidiChess :: Administration</title>
    <link rel="stylesheet" href="../../Style/output.css">
    <script src="../../Backend/admin/js/script.js"></script>
</head>

<body class="bg-[#302E2B] w-[70%] mx-auto">
    <div class="flex justify-center items-center mt-[2vh] gap-4">
        <img src="../../Assets/Images/Logo.png" alt="logo" class="w-[225px]">
        <hr class="bg-white h-[50px] w-[2px]">
        <p class="text-white text-2xl"><i>Administration Panel</i></p>
    </div>
    <a href="./" class="text-white"><i>
            << Back to Index</i></a>
    <div id="last_logins" class="text-white w-full mx-auto mt-[2vh]">
        <p class="text-white text-3xl">Last 500 Accesses</p>
        <div class="mt-[2vh] p-[20px] bg-[#1c1a19] rounded-[10px] w-full flex items-center gap-2">
            <label class="text-white">Enter User:</label>
            <input class="bg-[#22201e] px-2 py-1 outline-none text-white rounded-lg border-2 transition-colors duration-100 border-solid focus:border-[#739552] border-[#1c1a19]"
                placeholder="Username" id="getUserSearchUsername" type="text">
            <button id="getUserSearchBtn" class="GreenBtn py-1 mb-[0.625vh]" onclick="showUserLogs()">Search</button>
            <button id="clearResultsBtn" class="RedBtn py-1 mb-[0.625vh]" onclick="clearLogs()">Clear Results</button>
        </div>
        <div class="mt-[2vh] p-[20px] bg-[#1c1a19] rounded-[10px] w-full">
            <table class="w-full" id="lastLoginsTable">
                <tr class="overflow-y-auto">
                    <td>ID</td>
                    <td>User</td>
                    <td>IP</td>
                    <td>ISP</td>
                    <td>Country</td>
                    <td>Region</td>
                    <td>Device</td>
                    <td>OS</td>
                    <td>Client</td>
                    <td>Time</td>
                </tr>
            </table>
        </div>
    </div>
    <script>
        table = document.getElementById("lastLoginsTable");

        async function handleGlobalLastLogins() {
            const response = await fetch('../../Backend/Admin.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    action: 'getAccessLogs'
                })
            });

            const data = await response.json();
            users = data['logins']

            users.forEach(user => {
                let row = table.insertRow(-1);
                let cell1 = row.insertCell(0);
                let cell2 = row.insertCell(1);
                let cell3 = row.insertCell(2);
                let cell4 = row.insertCell(3);
                let cell5 = row.insertCell(4);
                let cell6 = row.insertCell(5);
                let cell7 = row.insertCell(6);
                let cell8 = row.insertCell(7);
                let cell9 = row.insertCell(8);
                let cell10 = row.insertCell(9);

                cell1.innerHTML = user.user_id;
                cell2.innerHTML = user.username;
                cell3.innerHTML = user.ip;
                cell4.innerHTML = user.isp;
                cell5.innerHTML = user.country;
                cell6.innerHTML = user.region;
                cell7.innerHTML = user.device;
                cell8.innerHTML = user.os;
                cell9.innerHTML = user.client;
                cell10.innerHTML = user.time;
            });
        }

        handleGlobalLastLogins();

        async function handleUserLastLogins() {
            let user = document.getElementById("getUserSearchUsername").value;
            if (user.length > 0) {

                const response = await fetch('../../Backend/Admin.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        action: 'getAccessLogsForUser',
                        username: user
                    })
                });

                const data = await response.json();

                users = data['logins']


                users.forEach(user => {
                    let row = table.insertRow(-1);
                    let cell1 = row.insertCell(0);
                    let cell2 = row.insertCell(1);
                    let cell3 = row.insertCell(2);
                    let cell4 = row.insertCell(3);
                    let cell5 = row.insertCell(4);
                    let cell6 = row.insertCell(5);
                    let cell7 = row.insertCell(6);
                    let cell8 = row.insertCell(7);
                    let cell9 = row.insertCell(8);
                    let cell10 = row.insertCell(9);

                    cell1.innerHTML = user.user_id;
                    cell2.innerHTML = user.username;
                    cell3.innerHTML = user.ip;
                    cell4.innerHTML = user.isp;
                    cell5.innerHTML = user.country;
                    cell6.innerHTML = user.region;
                    cell7.innerHTML = user.device;
                    cell8.innerHTML = user.os;
                    cell9.innerHTML = user.client;
                    cell10.innerHTML = user.time;
                });
            }
        }

        function clearLogs() {
            while (table.rows.length > 1) {
                table.deleteRow(1);
            }
            handleGlobalLastLogins();
        }

        function showUserLogs() {
            while (table.rows.length > 1) {
                table.deleteRow(1);
            }
            handleUserLastLogins();
        }
    </script>
</body>

</html>

</html>