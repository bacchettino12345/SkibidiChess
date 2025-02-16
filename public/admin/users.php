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
        function deleteUserBtn()
        {
            user = document.getElementById("deleteUserUsername").value;
            if(user != "")
            {   
                if(confirm("Are you sure?"))
                {
                    checkUserExistance(user).then(exists =>
                        {
                            if(exists)
                            {
                                deleteUser(user); 
                            }
                            else
                            {
                                alert("User does not exist.")
                            }
                        }
                    )
                }
            }
            else
            {
                alert("Username not specified.");
            }
        }

        function setPointsBtn()
        {
            user = document.getElementById('managePointsUsername').value;
            pts  = document.getElementById('managePointsPts').value;
            if(user != "")
            {
                checkUserExistance(user).then(exists => {
                    console.log(exists);
                    if(exists)
                    {
                        if(pts != "")
                        {
                            setPoints(user, pts);
                        }else
                        {
                            alert("Pts. number not specified");
                        } 
                    } else
                    {
                        alert("User does not exist.")
                    }
                }
                )
            }
            else
            {
                alert("Username not specified.");
            }
        }

        function clearUsers()
        {
            const usersTable = document.querySelector("#usersTable");
            while (usersTable.rows.length > 1) {
                usersTable.deleteRow(1);
            }
        }

        function showUsers() {
            if(document.querySelector('#getUserSearchUsername').value != "")
            {
                getUsers(document.querySelector('#getUserSearchUsername').value).then(users => {
                    if (!users) return;
                    const usersTable = document.querySelector("#usersTable");
                    clearUsers();
                    if(users.length === 0)
                    {
                        alert("No users found.");
                    }
                    else
                    {
                        users.forEach(user => {
                            const row = usersTable.insertRow();
                            row.insertCell(0).textContent = user.id;
                            row.insertCell(1).textContent = user.user;
                            row.insertCell(2).textContent = user.active;
                            row.insertCell(3).textContent = user.admin;
                            row.insertCell(4).textContent = user.pts;
                        });
                    }
                }).catch(error => {
                    alert('Error:', error);
                });    
            } else
            {
                clearUsers();
            }
        }
    </script>
</head>
<body class="bg-[#302E2B] w-[70%] mx-auto">
<div class="flex justify-center items-center mt-[2vh] gap-4">
        <img src="../../Assets/Images/Logo.png" alt="logo" class="w-[225px]">
        <hr class="bg-white h-[50px] w-[2px]">
        <p class="text-white text-3xl"><i>Administration Panel</i></p>        
    </div>
    <a href="./index.php" class="text-white"><i><< Back to Index</i></a>
    <div id="users" class="text-white w-full mx-auto mt-[2vh]">
        <p class="text-white text-3xl">Users</p>
        <div class="mt-[2vh] p-[20px] bg-[#1c1a19] rounded-[10px] w-full flex items-center gap-2">
            <label class="text-white">Enter User:</label>
            <input
                class="bg-[#22201e] px-2 py-1 outline-none text-white rounded-lg border-2 transition-colors duration-100 border-solid focus:border-[#739552] border-[#1c1a19]"
                
                placeholder="Username"
                id="getUserSearchUsername"
                type="text">
                <button id="getUserSearchBtn" class="GreenBtn py-1 mb-[0.625vh]" onclick="showUsers()">Search</button>
                <button id="getUserSearchBtn" class="RedBtn py-1 mb-[0.625vh]" onclick="clearUsers()">Clear Results</button>        
        
        </div>
        <div class="mt-[2vh] p-[20px] bg-[#1c1a19] rounded-[10px] w-full">
            <table class="w-full" id="usersTable">
                <tr>
                    <td>ID</td>
                    <td>User</td>
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
            <input
                class="bg-[#22201e] px-2 py-1 outline-none text-white rounded-lg border-2 transition-colors duration-100 border-solid focus:border-[#739552] border-[#1c1a19]"
                placeholder="Username"
                id="managePointsUsername"
                type="text">
            <label class="text-white">PTs:</label>
            <input
                class="bg-[#22201e] px-2 py-1 outline-none text-white rounded-lg border-2 transition-colors duration-100 border-solid focus:border-[#739552] border-[#1c1a19]"
                placeholder="Points"
                id="managePointsPts"
                type="number">
            <button id="managePointsBtn" class="GreenBtn py-1 mb-[0.625vh]" onclick="setPointsBtn()">Set Points</button>
    </div>
    <div id="deactivateUser" class="text-white w-full mx-auto mt-[2vh]">
        <p class="text-white text-3xl">Account Activation Status</p>
        <div class="mt-[2vh] p-[20px] bg-[#1c1a19] rounded-[10px] w-full flex items-center gap-2">
            <label class="text-white">Username:</label>
            <input
                class="bg-[#22201e] px-2 py-1 outline-none text-white rounded-lg border-2 transition-colors duration-100 border-solid focus:border-[#b92828] border-[#1c1a19]"
                placeholder="Deativate User"
                id="username"
                type="text">
            <button id="deleteUser" class="RedBtn py-1 mb-[0.625vh]">Deactivate</button>
            <button id="deleteUser" class="GreenBtn py-1 mb-[0.625vh]">Activate</button>
    </div>
    </div>
    <div id="makeAdmin" class="text-white w-full mx-auto mt-[2vh]">
    <p class="text-white text-3xl">Manage Admin Privileges</p>
        <div class="mt-[2vh] p-[20px] bg-[#1c1a19] rounded-[10px] w-full flex items-center gap-2">
            <label class="text-white">Enter User:</label>
            <input
                class="bg-[#22201e] px-2 py-1 outline-none text-white rounded-lg border-2 transition-colors duration-100 border-solid focus:border-[#b92828] border-[#1c1a19]"
                placeholder="Username"
                id="username"
                type="text">
            <button id="deleteUser" class="GreenBtn py-1 mb-[0.625vh]">Give Admin PrivS</button>
            <button id="deleteUser" class="RedBtn py-1 mb-[0.625vh]">Revoke Admin PrivS</button>
    </div>
    </div>
    <div id="addUser" class="text-white w-full mx-auto mt-[2vh] mb-10">
        <p class="text-white text-3xl">Add Users</p>
        <div class="mt-[2vh] p-[20px] bg-[#1c1a19] rounded-[10px] w-full flex items-center gap-2">
            <label class="text-white">Username</label>
            <input
                class="bg-[#22201e] px-2 py-1 outline-none text-white rounded-lg border-2 transition-colors duration-100 border-solid focus:border-[#739552] border-[#1c1a19]"
                placeholder="Insert Username"
                id="username"
                type="text">
            <label class="text-white">Password</label>
            <input
                class="bg-[#22201e] px-2 py-1 outline-none text-white rounded-lg border-2 transition-colors duration-100 border-solid focus:border-[#739552] border-[#1c1a19]"
                placeholder="Insert Passoword"
                id="password"
                type="password">
            <label class="text-white">isAdmin?</label>
            <input type="checkbox" name="admin" id="admin">
            <br>
            <button id="AddUsrBtn" class="GreenBtn py-1 mb-[0.625vh]">Add User</button>        
        </div>
        <div id="deleteUser" class="text-white w-full mx-auto mt-[2vh]">
            <p class="text-white text-3xl">Delete Users</p>
            <div class="mt-[2vh] p-[20px] bg-[#1c1a19] rounded-[10px] w-full flex items-center gap-2">
                <label class="text-white">Delete User:</label>
                <input
                    class="bg-[#22201e] px-2 py-1 outline-none text-white rounded-lg border-2 transition-colors duration-100 border-solid focus:border-[#b92828] border-[#1c1a19]"
                    placeholder="Delete User"
                    id="deleteUserUsername"
                    type="text">
                <button id="deleteUserBtn" class="RedBtn py-1 mb-[0.625vh]" onclick="deleteUserBtn()">Delete</button>
            </div>
        </div>
    </div>
</body>
</html>
</html>