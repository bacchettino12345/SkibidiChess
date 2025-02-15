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
    <script src="../../Backend/admin/js/script.js"></script>
    <script>
        function deleteUserBtn()
        {
            if(confirm("Are you sure?"))
            {
                deleteUser(document.getElementById("deleteUserUsername"));
            }
        }
    </script>
</head>
<body class="bg-[#302E2B] w-[70%] mx-auto">
<div class="flex justify-center items-center mt-[2vh] gap-4">
        <img src="../../Assets/Images/Logo.png" alt="logo" class="w-[20%]">
        <hr class="bg-white h-[50px] w-[2px]">
        <p class="text-white text-3xl"><i>Administration Panel</i></p>        
    </div>
    <a href="./index.php" class="text-white"><i><< Back to Index</i></a>
    <div id="users" class="text-white w-full mx-auto mt-[2vh]">
        <p class="text-white text-3xl">Users</p>
        <div class="mt-[2vh] p-[20px] bg-[#1c1a19] rounded-[10px] w-full">
            <table class="w-full">
                <tr>
                    <td>ID</td>
                    <td>User</td>
                    <td>isActive?</td>
                    <td>isAdmin?</td>
                    <td>PTS</td>
                </tr>
                <?php
                require '../../Backend/db_connection.php';
                $sql = "SELECT * FROM accounts";
                $result = $conn->query($sql);
                if ($result) {
                    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>".$row['id']."</td>";
                        echo "<td>".$row['user']."</td>";
                        echo "<td>".$row['active']."</td>";
                        echo "<td>".$row['admin']."</td>";
                        echo "<td>".$row['pts']."</td>";
                        echo "</tr>";
                    }
                }
                ?>

            </table>
        </div>
    </div>
    <div id="managePoints" class="text-white w-full mx-auto mt-[2vh]">
        <p class="text-white text-3xl">Set Points</p>
        <div class="mt-[2vh] p-[20px] bg-[#1c1a19] rounded-[10px] w-full flex items-center gap-2">
            <label for="username" class="text-white">Enter User:</label>
            <input
                class="bg-[#22201e] px-2 py-1 outline-none text-white rounded-lg border-2 transition-colors duration-100 border-solid focus:border-[#b92828] border-[#1c1a19]"
                name="user"
                placeholder="Username"
                id="username"
                type="text">
            <label for="username" class="text-white">PTs:</label>
            <input
                class="bg-[#22201e] px-2 py-1 outline-none text-white rounded-lg border-2 transition-colors duration-100 border-solid focus:border-[#b92828] border-[#1c1a19]"
                name="user"
                placeholder="Points"
                id="username"
                type="text">
            <button id="deleteUser" class="GreenBtn py-1 mb-[0.625vh]">Set Points</button>
    </div>
    <div id="deactivateUser" class="text-white w-full mx-auto mt-[2vh]">
        <p class="text-white text-3xl">Account Activation Status</p>
        <div class="mt-[2vh] p-[20px] bg-[#1c1a19] rounded-[10px] w-full flex items-center gap-2">
            <label for="username" class="text-white">Deactivate User:</label>
            <input
                class="bg-[#22201e] px-2 py-1 outline-none text-white rounded-lg border-2 transition-colors duration-100 border-solid focus:border-[#b92828] border-[#1c1a19]"
                name="user"
                placeholder="Deativate User"
                id="username"
                type="text">
            <button id="deleteUser" class="RedBtn py-1 mb-[0.625vh]">Deactivate</button>
        </div>
        <div class="mt-[2vh] p-[20px] bg-[#1c1a19] rounded-[10px] w-full flex items-center gap-2">
            <label for="username" class="text-white">Activate User:</label>
            <input
                class="bg-[#22201e] px-2 py-1 outline-none text-white rounded-lg border-2 transition-colors duration-100 border-solid focus:border-[#b92828] border-[#1c1a19]"
                name="user"
                placeholder="Ativate User"
                id="username"
                type="text">
            <button id="deleteUser" class="RedBtn py-1 mb-[0.625vh]">Activate</button>
        </div>
    </div>
    <div id="makeAdmin" class="text-white w-full mx-auto mt-[2vh]">
    <p class="text-white text-3xl">Manage Admin Privileges</p>
        <div class="mt-[2vh] p-[20px] bg-[#1c1a19] rounded-[10px] w-full flex items-center gap-2">
            <label for="username" class="text-white">Enter User to Promote:</label>
            <input
                class="bg-[#22201e] px-2 py-1 outline-none text-white rounded-lg border-2 transition-colors duration-100 border-solid focus:border-[#b92828] border-[#1c1a19]"
                name="user"
                placeholder="Username"
                id="username"
                type="text">
            <button id="deleteUser" class="RedBtn py-1 mb-[0.625vh]">Give Admin PrivS</button>
    </div>
        <div class="mt-[2vh] p-[20px] bg-[#1c1a19] rounded-[10px] w-full flex items-center gap-2">
            <label for="username" class="text-white">Enter User to revoke PrivS:</label>
            <input
                class="bg-[#22201e] px-2 py-1 outline-none text-white rounded-lg border-2 transition-colors duration-100 border-solid focus:border-[#b92828] border-[#1c1a19]"
                name="user"
                placeholder="Username"
                id="username"
                type="text">
            <button id="deleteUser" class="RedBtn py-1 mb-[0.625vh]">Revoke Admin PrivS</button>
        </div>
    </div>
    <div id="addUser" class="text-white w-full mx-auto mt-[2vh] mb-10">
        <p class="text-white text-3xl">Add Users</p>
        <div class="mt-[2vh] p-[20px] bg-[#1c1a19] rounded-[10px] w-full flex items-center gap-2">
            <label for="username" class="text-white">Username</label>
            <input
                class="bg-[#22201e] px-2 py-1 outline-none text-white rounded-lg border-2 transition-colors duration-100 border-solid focus:border-[#739552] border-[#1c1a19]"
                name="user"
                placeholder="Insert Username"
                id="username"
                type="text">
            <label for="username" class="text-white">Password</label>
            <input
                class="bg-[#22201e] px-2 py-1 outline-none text-white rounded-lg border-2 transition-colors duration-100 border-solid focus:border-[#739552] border-[#1c1a19]"
                name="user"
                placeholder="Insert Passoword"
                id="password"
                type="passowrd">
            <label for="username" class="text-white">isAdmin?</label>
            <input type="checkbox" name="admin" id="admin">
            <br>
            <button id="AddUsrBtn" class="GreenBtn py-1 mb-[0.625vh]">Add User</button>        
        </div>
        <div id="deleteUser" class="text-white w-full mx-auto mt-[2vh]">
            <p class="text-white text-3xl">Delete Users</p>
            <div class="mt-[2vh] p-[20px] bg-[#1c1a19] rounded-[10px] w-full flex items-center gap-2">
                <label for="username" class="text-white">Delete User:</label>
                <input
                    class="bg-[#22201e] px-2 py-1 outline-none text-white rounded-lg border-2 transition-colors duration-100 border-solid focus:border-[#b92828] border-[#1c1a19]"
                    name="user"
                    placeholder="Delete User"
                    id="deleteUserUsername"
                    type="text">
                <button id="deleteUser" class="RedBtn py-1 mb-[0.625vh]" onclick="deleteUserBtn()">Delete</button>
            </div>
        </div>
    </div>
</body>
</html>
</html>