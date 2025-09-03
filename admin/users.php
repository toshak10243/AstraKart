<?php
session_start();
include("../includes/db.php");

if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit;
}

// Block/Unblock user
if(isset($_GET['block'])){
    $user_id = $_GET['block'];
    $conn->query("UPDATE users SET status='Blocked' WHERE id='$user_id'");
}
if(isset($_GET['unblock'])){
    $user_id = $_GET['unblock'];
    $conn->query("UPDATE users SET status='Active' WHERE id='$user_id'");
}

// Fetch all users
$users = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Users - AstraKart</title>
<style>
body{font-family:Arial,sans-serif; margin:0; background:#f4f4f4;}
header{background:#2874f0; color:#fff; padding:15px; text-align:center; font-size:22px;}
.container{max-width:1200px; margin:20px auto; padding:0 20px;}
table{width:100%; border-collapse: collapse; background:#fff; box-shadow:0 2px 6px rgba(0,0,0,0.1);}
th, td{padding:12px; border-bottom:1px solid #ddd; text-align:center;}
th{background:#f1f1f1;}
a.btn{padding:6px 12px; border-radius:5px; text-decoration:none; color:#fff;}
a.block{background:#e53935;}
a.unblock{background:#28a745;}
</style>
</head>
<body>

<header>Admin - Manage Users</header>
<div class="container">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while($user = $users->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo $user['name']; ?></td>
                    <td><?php echo $user['email']; ?></td>
                    <td><?php echo $user['status']; ?></td>
                    <td>
                        <?php if($user['status']=="Active"): ?>
                            <a href="users.php?block=<?php echo $user['id']; ?>" class="btn block">Block</a>
                        <?php else: ?>
                            <a href="users.php?unblock=<?php echo $user['id']; ?>" class="btn unblock">Unblock</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
