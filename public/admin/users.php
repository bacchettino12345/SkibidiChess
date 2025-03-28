<?php
require './check_login_admin.php';
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
            function deleteUserBtn() {
                let user = document.getElementById("deleteUserUsername").value;
                if (user !== "") {
                    if (confirm("Are you sure?")) {
                        checkUserExistance(user).then(exists => {
                            if (exists) {
                                deleteUser(user); 
                            } else {
                                alert("User does not exist.");
                            }
                        });
                    }
                } else {
                    alert("Username not specified.");
                }
            }

            function setPointsBtn() {
                let user = document.getElementById('managePointsUsername').value;
                let pts = document.getElementById('managePointsPts').value;
                if (user !== "") {
                    checkUserExistance(user).then(exists => {
                        console.log(exists);
                        if (exists) {
                            if (pts !== "") {
                                setPoints(user, pts);
                            } else {
                                alert("Pts. number not specified");
                            }
                        } else {
                            alert("User does not exist.");
                        }
                    });
                } else {
                    alert("Username not specified.");
                }
            }

            function clearUsers() {
                const usersTable = document.querySelector("#usersTable");
                while (usersTable.rows.length > 1) {
                    usersTable.deleteRow(1);
                }
            }

            function showUsers() {
                if (document.querySelector('#getUserSearchUsername').value !== "") {
                    getUsers(document.querySelector('#getUserSearchUsername').value.trim()).then(users => {
                        if (!users) return;
                        const usersTable = document.querySelector("#usersTable");
                        clearUsers();
                        if (users.length === 0) {
                            alert("No users found.");
                        } else {
                            console.log(users.length);
                            users.forEach(user => {
                                const row = usersTable.insertRow();
                                row.insertCell(0).textContent = user.id;
                                row.insertCell(1).textContent = user.user;
                                row.insertCell(2).textContent = user.firstname;
                                row.insertCell(3).textContent = user.lastname;
                                row.insertCell(4).textContent = user.mail;
                                row.insertCell(5).textContent = user.active;
                                row.insertCell(6).textContent = user.admin;
                                row.insertCell(7).textContent = user.pts;
                                row.insertCell(8).textContent = user.last_login;
                            });
                        }
                    }).catch(error => {
                        alert('Error:', error);
                    });
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
                <button id="getUserSearchBtn" class="GreenBtn py-1 mb-[0.625vh]" onclick="showUsers()">Search</button>
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
                        <td>Last Login</td>
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
                    placeholder="Deactivate User" id="manageActivationUsername" type="text">
                <button id="deactivateBtn" class="RedBtn py-1 mb-[0.625vh]">Deactivate</button>
                <button id="activateBtn" class="GreenBtn py-1 mb-[0.625vh]">Activate</button>
            </div>
        </div>

        <div id="manageAdmin" class="text-white w-full mx-auto mt-[2vh]">
            <p class="text-white text-3xl">Manage Admin Privileges</p>
            <div class="mt-[2vh] p-[20px] bg-[#1c1a19] rounded-[10px] w-full flex items-center gap-2">
                <label class="text-white">Enter User:</label>
                <input class="bg-[#22201e] px-2 py-1 outline-none text-white rounded-lg border-2 transition-colors duration-100 border-solid focus:border-[#b92828] border-[#1c1a19]"
                    placeholder="Username" id="manageAdminUsername" type="text">
                <button id="giveAdminBtn" class="GreenBtn py-1 mb-[0.625vh]">Give Admin PrivS</button>
                <button id="revokeAdminBtn" class="RedBtn py-1 mb-[0.625vh]">Revoke Admin PrivS</button>
            </div>
        </div>

        <div id="changeUsername" class="text-white w-full mx-auto mt-[2vh]">
            <p class="text-white text-3xl">Change Username</p>
            <div class="mt-[2vh] p-[20px] bg-[#1c1a19] rounded-[10px] w-full flex items-center gap-2">
                <label class="text-white">Previous Username:</label>
                <input class="bg-[#22201e] px-2 py-1 outline-none text-white rounded-lg border-2 transition-colors duration-100 border-solid focus:border-[#b92828] border-[#1c1a19]"
                    placeholder="Username" id="oldUsername" type="text">
                <label class="text-white">New Username:</label>
                <input class="bg-[#22201e] px-2 py-1 outline-none text-white rounded-lg border-2 transition-colors duration-100 border-solid focus:border-[#b92828] border-[#1c1a19]"
                    placeholder="Username" id="newUsername" type="text">
                <button id="changeUsernameBtn" class="GreenBtn py-1 mb-[0.625vh]">Change</button>
            </div>
        </div>

        <div id="changePassword" class="text-white w-full mx-auto mt-[2vh]">
            <p class="text-white text-3xl">Change Password</p>
            <div class="mt-[2vh] p-[20px] bg-[#1c1a19] rounded-[10px] w-full flex flex-col gap-2">
            <div class="flex items-center gap-2">
                <label class="text-white">Username:</label>
                <input class="bg-[#22201e] px-2 py-1 outline-none text-white rounded-lg border-2 transition-colors duration-100 border-solid focus:border-[#b92828] border-[#1c1a19]"
                placeholder="Username" id="changePasswordUsername" type="text">
            </div>
            <div class="flex items-center gap-2">
                <label class="text-white">New Password:</label>
                <input class="bg-[#22201e] px-2 py-1 outline-none text-white rounded-lg border-2 transition-colors duration-100 border-solid focus:border-[#b92828] border-[#1c1a19]"
                placeholder="Password" id="newPassword" type="password">
                <label class="text-white">Confirm Password:</label>
                <input class="bg-[#22201e] px-2 py-1 outline-none text-white rounded-lg border-2 transition-colors duration-100 border-solid focus:border-[#b92828] border-[#1c1a19]"
                placeholder="Password" id="newPasswordConfirm" type="password">
                <button id="changePasswordBtn" class="RedBtn py-1 mb-[0.625vh]">Change Password</button>
            </div>
            </div>
        </div>

        <div id="addUser" class="text-white w-full mx-auto mt-[2vh] mb-10">
            <p class="text-white text-3xl">Add Users</p>
            <div class="mt-[2vh] p-[20px] bg-[#1c1a19] rounded-[10px] w-full flex items-center gap-2">
                <label class="text-white">Username:</label>
                <input class="bg-[#22201e] px-2 py-1 outline-none text-white rounded-lg border-2 transition-colors duration-100 border-solid focus:border-[#739552] border-[#1c1a19]"
                    placeholder="Insert Username" id="addUsername" type="text">
                <label class="text-white">Password:</label>
                <input class="bg-[#22201e] px-2 py-1 outline-none text-white rounded-lg border-2 transition-colors duration-100 border-solid focus:border-[#739552] border-[#1c1a19]"
                    placeholder="Insert Password" id="addPassword" type="password">
                <label class="text-white">isAdmin?</label>
                <input type="checkbox" name="admin" id="admin">
                <button id="AddUsrBtn" class="GreenBtn py-1 mb-[0.625vh]">Add User</button>
            </div>

            <div id="deleteUserSection" class="text-white w-full mx-auto mt-[2vh]">
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
