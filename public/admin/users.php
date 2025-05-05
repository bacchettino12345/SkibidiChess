<?php
require_once '../../Backend/SessionChecker.php';

use Skibidi\SessionChecker;

$sessionChecker = new SessionChecker();

if (!$sessionChecker->checkSession())
    header('Location: ../login.html');
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
        let selectedUser = null;

        // Show the user action panel
        function showUserActionPanel(userId, username, isActive, isAdmin, points) {
            selectedUser = {
                id: userId,
                username: username,
                active: isActive === 'Yes',
                admin: isAdmin === 'Yes',
                points: points
            };

            // Fill the form fields
            document.getElementById('actionPanelUsername').textContent = username;
            document.getElementById('actionPanelPoints').value = points;
            
            // Set button states
            document.getElementById('activateBtn').style.display = isActive === 'Yes' ? 'none' : 'block';
            document.getElementById('deactivateBtn').style.display = isActive === 'Yes' ? 'block' : 'none';
            document.getElementById('giveAdminBtn').style.display = isAdmin === 'Yes' ? 'none' : 'block';
            document.getElementById('revokeAdminBtn').style.display = isAdmin === 'Yes' ? 'block' : 'none';

            // Show the panel
            document.getElementById('userActionPanel').classList.remove('hidden');
        }

        // Close the user action panel
        function closeUserActionPanel() {
            document.getElementById('userActionPanel').classList.add('hidden');
            selectedUser = null;
        }

        async function deleteUserBtn() {
            const username = selectedUser ? selectedUser.username : document.getElementById('deleteUserUsername').value;
            
            if (!username) {
                alert("Please enter a username or select a user");
                return;
            }
            
            if (confirm("The action is irreversible.\nAre you sure?")) {
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
                if (data.success) {
                    closeUserActionPanel();
                    showUsers(); // Refresh user list
                }
            }
        }

        async function setActiveBtn(active) {
            const username = selectedUser ? selectedUser.username : document.getElementById('manageActivationUsername').value;
            
            if (!username) {
                alert("Please enter a username or select a user");
                return;
            }
            
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
            if (data.success) {
                if (selectedUser) {
                    selectedUser.active = active;
                    document.getElementById('activateBtn').style.display = active ? 'none' : 'block';
                    document.getElementById('deactivateBtn').style.display = active ? 'block' : 'none';
                }
                showUsers(); // Refresh user list
            }
        }

        async function setAdminBtn(admin) {
            const username = selectedUser ? selectedUser.username : document.getElementById('manageAdminUsername').value;
            
            if (!username) {
                alert("Please enter a username or select a user");
                return;
            }
            
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
            if (data.success) {
                if (selectedUser) {
                    selectedUser.admin = admin;
                    document.getElementById('giveAdminBtn').style.display = admin ? 'none' : 'block';
                    document.getElementById('revokeAdminBtn').style.display = admin ? 'block' : 'none';
                }
                showUsers(); // Refresh user list
            }
        }

        async function setPointsBtn() {
            const username = selectedUser ? selectedUser.username : document.getElementById('managePointsUsername').value;
            const pts = selectedUser ? document.getElementById('actionPanelPoints').value : document.getElementById('managePointsPts').value;
            
            if (!username) {
                alert("Please enter a username or select a user");
                return;
            }
            
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
            if (data.success) {
                if (selectedUser) {
                    selectedUser.points = pts;
                }
                showUsers(); // Refresh user list
            }
        }

        function clearUsers() {
            const usersTable = document.querySelector("#usersTable");
            while (usersTable.rows.length > 1) {
                usersTable.deleteRow(1);
            }
            closeUserActionPanel();
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
                        row.className = "border-b border-[#302e2b] hover:bg-[#252321] cursor-pointer";
                        row.onclick = function() {
                            showUserActionPanel(user.id, user.username, user.active ? 'Yes' : 'No', user.admin ? 'Yes' : 'No', user.pts);
                        };
                        
                        let cell1 = row.insertCell(0);
                        let cell2 = row.insertCell(1);
                        let cell3 = row.insertCell(2);
                        let cell4 = row.insertCell(3);
                        let cell5 = row.insertCell(4);
                        let cell6 = row.insertCell(5);
                        let cell7 = row.insertCell(6);
                        let cell8 = row.insertCell(7);

                        cell1.className = "py-2 px-1 md:px-2";
                        cell2.className = "py-2 px-1 md:px-2";
                        cell3.className = "py-2 px-1 md:px-2";
                        cell4.className = "py-2 px-1 md:px-2";
                        cell5.className = "py-2 px-1 md:px-2";
                        cell6.className = "py-2 px-1 md:px-2";
                        cell7.className = "py-2 px-1 md:px-2";
                        cell8.className = "py-2 px-1 md:px-2";

                        cell1.innerHTML = user.id;
                        cell2.innerHTML = user.username;
                        cell3.innerHTML = user.firstname;
                        cell4.innerHTML = user.lastname;
                        cell5.innerHTML = user.email;
                        user.active ? cell6.innerHTML = 'Yes' : cell6.innerHTML = 'No';
                        user.admin ? cell7.innerHTML = 'Yes' : cell7.innerHTML = 'No';
                        cell8.innerHTML = user.pts;
                    });
                }
            } else {
                clearUsers();
            }
        }
    </script>
</head>

<body class="bg-[#302E2B] w-full md:w-[90%] lg:w-[80%] xl:w-[70%] mx-auto px-4 md:px-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-center items-center mt-4 md:mt-8 gap-2 md:gap-4">
        <img src="../../Assets/Images/Logo.png" alt="logo" class="w-[150px] md:w-[225px]">
        <hr class="hidden md:block bg-white h-[50px] w-[2px]">
        <p class="text-white text-xl md:text-2xl text-center md:text-left"><i>Administration Panel</i></p>
    </div>
    
    <!-- Back Button -->
    <a href="./index.php" class="text-white block mt-4"><i>&lt;&lt; Back to Index</i></a>

    <!-- User Action Panel (initially hidden) -->
    <div id="userActionPanel" class="fixed inset-0 flex Banner items-center justify-center z-50 hidden Widget">
        <div class="bg-[#1c1a19] text-white p-5 pb-7 rounded-lg w-full max-w-md mx-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">User Actions: <span id="actionPanelUsername" class="font-normal"></span></h2>
                <button onclick="closeUserActionPanel()" class="text-gray-400 hover:text-white text-3xl">&times;</button>
            </div>
            
            <div class="space-y-4">
                <!-- Points Management -->
                <div class="flex flex-col gap-2">
                    <label class="font-semibold">Set Points:</label>
                    <div class="flex gap-2">
                        <input id="actionPanelPoints" type="number" class="bg-[#22201e] px-2 py-1 outline-none text-white rounded-lg border-2 transition-colors duration-100 border-solid focus:border-[#739552] border-[#1c1a19] flex-grow">
                        <button onclick="setPointsBtn()" class="GreenBtn px-3 py-1">Apply</button>
                    </div>
                </div>
                
                <!-- Account Status -->
                <div class="flex flex-col gap-2">
                    <label class="font-semibold">Account Status:</label>
                    <div class="flex gap-2">
                        <button id="deactivateBtn" onclick="setActiveBtn(false)" class="RedBtn px-3 py-1 flex-grow">Deactivate</button>
                        <button id="activateBtn" onclick="setActiveBtn(true)" class="GreenBtn px-3 py-1 flex-grow">Activate</button>
                    </div>
                </div>
                
                <!-- Admin Status -->
                <div class="flex flex-col gap-2">
                    <label class="font-semibold">Admin Status:</label>
                    <div class="flex gap-2">
                        <button id="revokeAdminBtn" onclick="setAdminBtn(false)" class="RedBtn px-3 py-1 flex-grow">Revoke Admin</button>
                        <button id="giveAdminBtn" onclick="setAdminBtn(true)" class="RedBtn px-3 py-1 flex-grow">Give Admin</button>
                    </div>
                </div>
                
                <!-- Delete Account -->
                <div class="flex flex-col gap-2">
                    <label class="font-semibold">Delete Account:</label>
                    <button onclick="deleteUserBtn()" class="RedBtn px-3 py-1">Delete User</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Search Section -->
    <div id="users" class="text-white w-full mx-auto mt-4 md:mt-8">
        <p class="text-white text-2xl md:text-3xl">Users</p>
        <div class="mt-4 p-3 md:p-5 bg-[#1c1a19] rounded-[10px] w-full flex flex-col md:flex-row md:items-center gap-2">
            <label class="text-white md:self-center">Search:</label>
            <input class="bg-[#22201e] px-2 py-1 outline-none text-white rounded-lg border-2 transition-colors duration-100 border-solid focus:border-[#739552] border-[#1c1a19] w-full md:w-auto md:h-8"
                placeholder="Search" id="getUserSearchUsername" type="text">
            <div class="flex gap-2 w-full md:w-auto md:flex-shrink-0">
                <button id="getUserSearchBtn" class="GreenBtn py-1 px-3 w-full md:w-auto md:h-8 md:self-center" onclick="showUsers()">Search</button>
                <button id="clearResultsBtn" class="RedBtn py-1 px-3 w-full md:w-auto md:h-8 md:self-center" onclick="clearUsers()">Clear</button>
            </div>
        </div>
        
        <!-- Users Table -->
        <div class="mt-4 p-3 md:p-5 bg-[#1c1a19] rounded-[10px] w-full overflow-x-auto">
            <table class="w-full text-sm md:text-base" id="usersTable">
                <thead>
                    <tr class="border-b border-[#302e2b]">
                        <th class="text-left py-2 px-1 md:px-2">ID</th>
                        <th class="text-left py-2 px-1 md:px-2">User</th>
                        <th class="text-left py-2 px-1 md:px-2">Firstname</th>
                        <th class="text-left py-2 px-1 md:px-2">Lastname</th>
                        <th class="text-left py-2 px-1 md:px-2">Email</th>
                        <th class="text-left py-2 px-1 md:px-2">isActive?</th>
                        <th class="text-left py-2 px-1 md:px-2">isAdmin?</th>
                        <th class="text-left py-2 px-1 md:px-2">PTS</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Table rows will be inserted here by JavaScript -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Original panels kept for backward compatibility -->
    <div class="text-white text-3xl mt-8 mb-4 pt-4">
        <p>Alternative Individual Management Panels:</p>
    </div>

    <!-- Set Points Section -->
    <div id="managePoints" class="text-white w-full mx-auto mt-4 md:mt-8">
        <p class="text-white text-2xl md:text-2xl">Set Points</p>
        <div class="mt-4 p-3 md:p-5 bg-[#1c1a19] rounded-[10px] w-full flex flex-col md:flex-row md:items-center gap-2">
            <div class="flex flex-col md:flex-row gap-2 w-full md:w-auto">
                <label class="text-white md:self-center">Enter User:</label>
                <input class="bg-[#22201e] px-2 py-1 outline-none text-white rounded-lg border-2 transition-colors duration-100 border-solid focus:border-[#739552] border-[#1c1a19] w-full md:w-auto md:h-8"
                    placeholder="Username" id="managePointsUsername" type="text">
            </div>
            <div class="flex flex-col md:flex-row gap-2 w-full md:w-auto">
                <label class="text-white md:self-center">PTs:</label>
                <input class="bg-[#22201e] px-2 py-1 outline-none text-white rounded-lg border-2 transition-colors duration-100 border-solid focus:border-[#739552] border-[#1c1a19] w-full md:w-auto md:h-8"
                    placeholder="Points" id="managePointsPts" type="number">
            </div>
            <button id="managePointsBtn" class="GreenBtn py-1 px-3 mt-2 md:mt-0 w-full md:w-auto md:h-8 md:self-center" onclick="setPointsBtn()">Set Points</button>
        </div>
    </div>

    <!-- Account Activation Section -->
    <div id="manageActivation" class="text-white w-full mx-auto mt-4 md:mt-8">
        <p class="text-white text-2xl md:text-2xl">Account Activation Status</p>
        <div class="mt-4 p-3 md:p-5 bg-[#1c1a19] rounded-[10px] w-full flex flex-col md:flex-row md:items-center gap-2">
            <label class="text-white md:self-center">Username:</label>
            <input class="bg-[#22201e] px-2 py-1 outline-none text-white rounded-lg border-2 transition-colors duration-100 border-solid focus:border-[#b92828] border-[#1c1a19] w-full md:w-auto md:h-8"
                placeholder="Username" id="manageActivationUsername" type="text">
            <div class="flex gap-2 w-full md:w-auto md:flex-shrink-0">
                <button id="deactivateManualBtn" class="RedBtn py-1 px-3 w-full md:w-auto md:h-8 md:self-center" onclick="setActiveBtn(false)">Deactivate (BAN)</button>
                <button id="activateManualBtn" class="GreenBtn py-1 px-3 w-full md:w-auto md:h-8 md:self-center" onclick="setActiveBtn(true)">Activate</button>
            </div>
        </div>
    </div>

    <!-- Admin Privileges Section -->
    <div id="manageAdmin" class="text-white w-full mx-auto mt-4 md:mt-8">
        <p class="text-white text-2xl md:text-2xl">Manage Admin Privileges</p>
        <div class="mt-4 p-3 md:p-5 bg-[#1c1a19] rounded-[10px] w-full flex flex-col md:flex-row md:items-center gap-2">
            <label class="text-white md:self-center">Enter User:</label>
            <input class="bg-[#22201e] px-2 py-1 outline-none text-white rounded-lg border-2 transition-colors duration-100 border-solid focus:border-[#b92828] border-[#1c1a19] w-full md:w-auto md:h-8"
                placeholder="Username" id="manageAdminUsername" type="text">
            <div class="flex gap-2 w-full md:w-auto md:flex-shrink-0">
                <button id="giveAdminManualBtn" class="RedBtn py-1 px-3 w-full md:w-auto md:h-8 md:self-center" onclick="setAdminBtn(true)">Give Admin</button>
                <button id="revokeAdminManualBtn" class="RedBtn py-1 px-3 w-full md:w-auto md:h-8 md:self-center" onclick="setAdminBtn(false)">Revoke Admin</button>
            </div>
        </div>
    </div>

    <!-- Delete User Section -->
    <div id="deleteUserSection" class="text-white w-full mx-auto mt-4 md:mt-8 mb-8 md:mb-12">
        <p class="text-white text-2xl md:text-2xl">Delete Users</p>
        <div class="mt-4 p-3 md:p-5 bg-[#1c1a19] rounded-[10px] w-full flex flex-col md:flex-row md:items-center gap-2">
            <label class="text-white md:self-center">Delete User:</label>
            <input class="bg-[#22201e] px-2 py-1 outline-none text-white rounded-lg border-2 transition-colors duration-100 border-solid focus:border-[#b92828] border-[#1c1a19] w-full md:w-auto md:h-8"
                placeholder="Delete User" id="deleteUserUsername" type="text">
            <button id="deleteUserManualBtn" class="RedBtn py-1 px-3 w-full md:w-auto md:h-8 md:self-center" onclick="deleteUserBtn()">Delete</button>
        </div>
    </div>

</body>

</html>