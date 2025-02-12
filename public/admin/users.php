<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="widtd=device-widtd, initial-scale=1.0">
    <title>SkibidiChess :: Administration</title>
    <link rel="stylesheet" href="../../Style/output.css">
</head>
<body class="bg-[#302E2B]">
    <img src="../../Assets/Images/Logo.png" alt="logo" class="w-[15%] mx-auto mt-[2vh]">
    <div id="users" class="text-white w-[70%] mx-auto">
        <p class="text-white text-3xl">Users</p>
        <div class="mt-[2vh] p-[20px] bg-[#1c1a19] rounded-[10px] w-full">
            <table class="w-full">
                <tr>
                    <td>ID</td>
                    <td>User</td>
                    <td>isActive?</td>
                    <td>isAdmin?</td>
                    <td>Actions</td>
                </tr>
                <?php
                    require_once '../../Backend/db_connection.php';
                    $sql = "SELECT * FROM accounts";
                    $result = $conn->query($sql);
                    if ($result) {
                        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr>";
                            echo "<td>".$row['id']."</td>";
                            echo "<td>".$row['user']."</td>";
                            echo "<td>".$row['active']."</td>";
                            echo "<td>".$row['admin']."</td>";
                            echo "<td><!-- Actions here --></td>";
                            echo "</tr>";
                        }
                    }
                ?>
            </table>
        </div>
    </div>
    <div id="active_tokens" class="text-white w-[70%] mx-auto mt-[2vh]">
        <p class="text-white text-3xl">Active Tokens</p>
        <div class="mt-[2vh] p-[20px] bg-[#1c1a19] rounded-[10px] w-full">
            <table class="w-full">
                <tr>
                    <td>Token</td>
                    <td>Date Generation</td>
                    <td>LMx</td>
                    <td>LMy</td>
                    <td>Actions</td>
                </tr>
                <?php
                    $sql = "SELECT * FROM tokens";
                    $result = $conn->query($sql);
                    if ($result) {
                        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr>";
                            echo "<td>".$row['id']."</td>";
                            echo "<td>".$row['date']."</td>";
                            echo "<td>".$row['last_move_x']."</td>";
                            echo "<td>".$row['last_move_y']."</td>";
                            echo "</tr>";
                        }
                    }
                    ?>
            </table>
        </div>
    </div>
</body>
</html>
</html>