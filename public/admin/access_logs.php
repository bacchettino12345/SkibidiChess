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
    <script src="../../Backend/admin/js/script.js"></script>
</head>

<body class="bg-[#302E2B] w-full md:w-[90%] lg:w-[80%] xl:w-[70%] mx-auto px-4 md:px-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-center items-center mt-4 md:mt-8 gap-2 md:gap-4">
        <img src="../../Assets/Images/Logo.png" alt="logo" class="w-[150px] md:w-[225px]">
        <hr class="hidden md:block bg-white h-[50px] w-[2px]">
        <p class="text-white text-xl md:text-2xl text-center md:text-left"><i>Administration Panel</i></p>
    </div>
    
    <!-- Back Button -->
    <a href="./" class="text-white block mt-4"><i>&lt;&lt; Back to Index</i></a>
    
    <!-- Main Content -->
    <div id="last_logins" class="text-white w-full mx-auto mt-4 md:mt-8">
        <p class="text-white text-2xl md:text-3xl">Last 500 Accesses</p>
        
        <!-- Search Form -->
        <div class="mt-4 p-3 md:p-5 bg-[#1c1a19] rounded-[10px] w-full flex flex-col md:flex-row md:items-center gap-2">
            <label class="text-white md:self-center">Enter User:</label>
            <input class="bg-[#22201e] px-2 py-1 outline-none text-white rounded-lg border-2 transition-colors duration-100 border-solid focus:border-[#739552] border-[#1c1a19] w-full md:w-auto md:h-8"
                placeholder="Username" id="getUserSearchUsername" type="text">
            <div class="flex gap-2 w-full md:w-auto md:flex-shrink-0">
                <button id="getUserSearchBtn" class="GreenBtn py-1 px-3 w-full md:w-auto md:h-8 md:self-center" onclick="showUserLogs()">Search</button>
                <button id="clearResultsBtn" class="RedBtn py-1 px-3 w-full md:w-auto md:h-8 md:self-center" onclick="clearLogs()">Clear</button>
            </div>
        </div>
        
        <!-- Table Container -->
        <div class="mt-4 p-3 md:p-5 bg-[#1c1a19] rounded-[10px] w-full overflow-x-auto">
            <table class="w-full text-sm md:text-base" id="lastLoginsTable">
                <thead>
                    <tr class="border-b border-[#302e2b]">
                        <th class="text-left py-2 px-1 md:px-2">ID</th>
                        <th class="text-left py-2 px-1 md:px-2">User</th>
                        <th class="text-left py-2 px-1 md:px-2">IP</th>
                        <th class="text-left py-2 px-1 md:px-2">ISP</th>
                        <th class="text-left py-2 px-1 md:px-2">Country</th>
                        <th class="text-left py-2 px-1 md:px-2">Region</th>
                        <th class="text-left py-2 px-1 md:px-2">Device</th>
                        <th class="text-left py-2 px-1 md:px-2">OS</th>
                        <th class="text-left py-2 px-1 md:px-2">Client</th>
                        <th class="text-left py-2 px-1 md:px-2">Time</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Table rows will be inserted here by JavaScript -->
                </tbody>
            </table>
        </div>
    </div>
    
    <script>
        const table = document.getElementById("lastLoginsTable").getElementsByTagName('tbody')[0];

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
                row.className = "border-b border-[#302e2b] hover:bg-[#252321]";
                
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

                cell1.className = "py-2 px-1 md:px-2";
                cell2.className = "py-2 px-1 md:px-2";
                cell3.className = "py-2 px-1 md:px-2";
                cell4.className = "py-2 px-1 md:px-2";
                cell5.className = "py-2 px-1 md:px-2";
                cell6.className = "py-2 px-1 md:px-2";
                cell7.className = "py-2 px-1 md:px-2";
                cell8.className = "py-2 px-1 md:px-2";
                cell9.className = "py-2 px-1 md:px-2";
                cell10.className = "py-2 px-1 md:px-2";

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
                users = data['logins'];

                users.forEach(user => {
                    let row = table.insertRow(-1);
                    row.className = "border-b border-[#302e2b] hover:bg-[#252321]";
                    
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

                    cell1.className = "py-2 px-1 md:px-2";
                    cell2.className = "py-2 px-1 md:px-2";
                    cell3.className = "py-2 px-1 md:px-2";
                    cell4.className = "py-2 px-1 md:px-2";
                    cell5.className = "py-2 px-1 md:px-2";
                    cell6.className = "py-2 px-1 md:px-2";
                    cell7.className = "py-2 px-1 md:px-2";
                    cell8.className = "py-2 px-1 md:px-2";
                    cell9.className = "py-2 px-1 md:px-2";
                    cell10.className = "py-2 px-1 md:px-2";

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
            while (table.rows.length > 0) {
                table.deleteRow(0);
            }
            handleGlobalLastLogins();
        }

        function showUserLogs() {
            while (table.rows.length > 0) {
                table.deleteRow(0);
            }
            handleUserLastLogins();
        }
    </script>

</body>

</html>