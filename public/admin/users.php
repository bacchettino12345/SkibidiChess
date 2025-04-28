<?php
require_once '../../Backend/SessionChecker.php';

use Skibidi\SessionChecker;

$sessionChecker = new SessionChecker();

if (!$sessionChecker->checkSession())
    header('Location: login.html');
else if (!$sessionChecker->checkAdmin())
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
    <script>
        async function deleteUserBtn() {
            if (confirm("The action is irreversible.\nAre you sure?")) {
                let username = document.getElementById('deleteUserUsername').value;
                const response = await fetch('../../Backend/Admin.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        action: 'deleteUser',
                        username: username
                    })
                });

                const data = await response.json();
                alert(data.message);
            }

        }

        async function setActiveBtn(active) {

            let username = document.getElementById('manageActivationUsername').value;
            const response = await fetch('../../Backend/Admin.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    action: 'setActive',
                    username: username,
                    active: active
                })
            });

            const data = await response.json();
            alert(data.message);
        }

        async function setAdminBtn(admin) {

            let username = document.getElementById('manageAdminUsername').value;
            const response = await fetch('../../Backend/Admin.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    action: 'setAdmin',
                    username: username,
                    admin: admin
                })
            });

            const data = await response.json();
            alert(data.message);
        }

        async function setPointsBtn() {
            let username = document.getElementById('managePointsUsername').value;
            let pts = document.querySelector('#managePointsPts').value;
            if (pts == 0) {
                if (!confirm("This will reset the user points to 0.\nAre you sure to perform this action?")) {
                    return;
                }
            }
            const response = await fetch('../../Backend/Admin.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    action: 'setPoints',
                    username: username,
                    points: pts

                })
            });

            const data = await response.json();

            alert(data.message);
        }

        function clearUsers() {
            const usersTable = document.querySelector("#usersTable");
            while (usersTable.rows.length > 1) {
                usersTable.deleteRow(1);
            }
        }

        async function showUsers() {
            const usersTable = document.querySelector("#usersTable");
            username = document.querySelector('#getUserSearchUsername').value;
            if (username !== "") {
                const response = await fetch('../../Backend/Admin.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        action: 'userSearch',
                        username: username
                    })
                });

                const data = await response.json();
                if (data['success'] == false)
                    alert(data['message']);
                else {
                    users = data['users']
                    clearUsers();
                    users.forEach(user => {
                        let row = usersTable.insertRow(-1);
                        let cell1 = row.insertCell(0);
                        let cell2 = row.insertCell(1);
                        let cell3 = row.insertCell(2);
                        let cell4 = row.insertCell(3);
                        let cell5 = row.insertCell(4);
                        let cell6 = row.insertCell(5);
                        let cell7 = row.insertCell(6);
                        let cell8 = row.insertCell(7);

                        cell1.innerHTML = user.id;
                        cell2.innerHTML = user.username;
                        cell3.innerHTML = user.firstname;
                        cell4.innerHTML = user.lastname;
                        cell5.innerHTML = user.email;
                        cell6.innerHTML = user.active;
                        cell7.innerHTML = user.admin;
                        cell8.innerHTML = user.pts;
                    });

                }
            } else {
                clearUsers();
            }

        }
    </script>
</head>

<body class="bg-[#302E2B] w-[70%] mx-auto">
    <div class="flex justify-center items-center mt-[2vh] gap-4">
        <img src="../../Assets/Images/Logo.png" alt="logo" class="w-[225px]">
        <hr class="bg-white h-[50px] w-[2px]">
        <p class="text-white text-2xl"><i>Administration Panel</i></p>
    </div>
    <a href="./index.php" class="text-white"><i>&lt;&lt; Back to Index</i></a>

    <div id="users" class="text-white w-full mx-auto mt-[2vh]">
        <p class="text-white text-3xl">Users</p>
        <div class="mt-[2vh] p-[20px] bg-[#1c1a19] rounded-[10px] w-full flex items-center gap-2">
            <label class="text-white">Search:</label>
            <input class="bg-[#22201e] px-2 py-1 outline-none text-white rounded-lg border-2 transition-colors duration-100 border-solid focus:border-[#739552] border-[#1c1a19]"
                placeholder="Search" id="getUserSearchUsername" type="text">
            <button id="getUserSearchBtn" class="GreenBtn py-1 mb-[0.625vh]" onclick="showUsers()">Search / Reload results</button>
            <button id="clearResultsBtn" class="RedBtn py-1 mb-[0.625vh]" onclick="clearUsers()">Clear Results</button>
        </div>
        <div class="mt-[2vh] p-[20px] bg-[#1c1a19] rounded-[10px] w-full">
            <table class="w-full" id="usersTable">
                <tr>
                    <td>ID</td>
                    <td>User</td>
                    <td>Firstname</td>
                    <td>Lastname</td>
                    <td>Email</td>
                    <td>isActive?</td>
                    <td>isAdmin?</td>
                    <td>PTS</td>
                </tr>
            </table>
        </div>
    </div>

    <div id="managePoints" class="text-white w-full mx-auto mt-[2vh]">
        <p class="text-white text-3xl">Set Points</p>
        <div class="mt-[2vh] p-[20px] bg-[#1c1a19] rounded-[10px] w-full flex items-center gap-2">
            <label class="text-white">Enter User:</label>
            <input class="bg-[#22201e] px-2 py-1 outline-none text-white rounded-lg border-2 transition-colors duration-100 border-solid focus:border-[#739552] border-[#1c1a19]"
                placeholder="Username" id="managePointsUsername" type="text">
            <label class="text-white">PTs:</label>
            <input class="bg-[#22201e] px-2 py-1 outline-none text-white rounded-lg border-2 transition-colors duration-100 border-solid focus:border-[#739552] border-[#1c1a19]"
                placeholder="Points" id="managePointsPts" type="number">
            <button id="managePointsBtn" class="GreenBtn py-1 mb-[0.625vh]" onclick="setPointsBtn()">Set Points</button>
        </div>
    </div>

    <div id="manageActivation" class="text-white w-full mx-auto mt-[2vh]">
        <p class="text-white text-3xl">Account Activation Status</p>
        <div class="mt-[2vh] p-[20px] bg-[#1c1a19] rounded-[10px] w-full flex items-center gap-2">
            <label class="text-white">Username:</label>
            <input class="bg-[#22201e] px-2 py-1 outline-none text-white rounded-lg border-2 transition-colors duration-100 border-solid focus:border-[#b92828] border-[#1c1a19]"
                placeholder="Username" id="manageActivationUsername" type="text">
            <button id="deactivateBtn" class="RedBtn py-1 mb-[0.625vh]" onclick="setActiveBtn(false)">Deactivate</button>
            <button id="activateBtn" class="GreenBtn py-1 mb-[0.625vh]" onclick="setActiveBtn(true)">Activate</button>
        </div>
    </div>

    <div id="manageAdmin" class="text-white w-full mx-auto mt-[2vh]">
        <p class="text-white text-3xl">Manage Admin Privileges</p>
        <div class="mt-[2vh] p-[20px] bg-[#1c1a19] rounded-[10px] w-full flex items-center gap-2">
            <label class="text-white">Enter User:</label>
            <input class="bg-[#22201e] px-2 py-1 outline-none text-white rounded-lg border-2 transition-colors duration-100 border-solid focus:border-[#b92828] border-[#1c1a19]"
                placeholder="Username" id="manageAdminUsername" type="text">
            <button id="giveAdminBtn" class="RedBtn py-1 mb-[0.625vh]" onclick="setAdminBtn(true)">Give Admin PrivS</button>
            <button id="revokeAdminBtn" class="RedBtn py-1 mb-[0.625vh]" onclick="setAdminBtn(false)">Revoke Admin PrivS</button>
        </div>
    </div>


    <div id="deleteUserSection" class="text-white w-full mx-auto mt-[2vh] mb-12">
        <p class="text-white text-3xl">Delete Users</p>
        <div class="mt-[2vh] p-[20px] bg-[#1c1a19] rounded-[10px] w-full flex items-center gap-2">
            <label class="text-white">Delete User:</label>
            <input class="bg-[#22201e] px-2 py-1 outline-none text-white rounded-lg border-2 transition-colors duration-100 border-solid focus:border-[#b92828] border-[#1c1a19]"
                placeholder="Delete User" id="deleteUserUsername" type="text">
            <button id="deleteUserBtn" class="RedBtn py-1 mb-[0.625vh]" onclick="deleteUserBtn()">Delete</button>
        </div>
    </div>
    </div>
</body>

</html>