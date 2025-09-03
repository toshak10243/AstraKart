<?php
session_start();
include("../includes/db.php");

// Check if admin logged in
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit;
}

// Fetch total counts
$total_orders = $conn->query("SELECT COUNT(*) AS count FROM orders")->fetch_assoc()['count'];
$total_users = $conn->query("SELECT COUNT(*) AS count FROM users")->fetch_assoc()['count'];
$total_products = $conn->query("SELECT COUNT(*) AS count FROM products")->fetch_assoc()['count'];
$pending_orders = $conn->query("SELECT COUNT(*) AS count FROM orders WHERE status='Pending'")->fetch_assoc()['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard - AstraKart</title>
<style>
body{font-family:Arial,sans-serif; margin:0; background:#f4f4f4;}
header{background:#2874f0; color:#fff; padding:15px; text-align:center; font-size:22px;}
.container{max-width:1100px; margin:30px auto; display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:20px; padding:0 20px;}
.card{background:#fff; padding:30px; border-radius:12px; box-shadow:0 4px 10px rgba(0,0,0,0.1); text-align:center;}
.card h3{margin-bottom:15px; color:#333;}
.card p{font-size:22px; font-weight:bold; color:#ff6f00;}
@media(max-width:768px){.container{grid-template-columns:1fr;}}
</style>
</head>
<body>

<header>Admin Dashboard</header>

<div class="container">
    <div class="card">
        <h3>Total Orders</h3>
        <p><?php echo $total_orders; ?></p>
    </div>
    <div class="card">
        <h3>Total Users</h3>
        <p><?php echo $total_users; ?></p>
    </div>
    <div class="card">
        <h3>Total Products</h3>
        <p><?php echo $total_products; ?></p>
    </div>
    <div class="card">
        <h3>Pending Orders</h3>
        <p><?php echo $pending_orders; ?></p>
    </div>
</div>

</body>
</html>
