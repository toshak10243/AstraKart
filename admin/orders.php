<?php
session_start();
include("../includes/db.php");

if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit;
}

// Update order status
if(isset($_POST['update_status'])){
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    $conn->query("UPDATE orders SET status='$status' WHERE order_id='$order_id'");
}

// Fetch all orders
$orders = $conn->query("SELECT * FROM orders ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Orders - AstraKart</title>
<style>
body{font-family:Arial,sans-serif; margin:0; background:#f4f4f4;}
header{background:#2874f0; color:#fff; padding:15px; text-align:center; font-size:22px;}
.container{max-width:1200px; margin:20px auto; padding:0 20px;}
table{width:100%; border-collapse: collapse; background:#fff; box-shadow:0 2px 6px rgba(0,0,0,0.1);}
th, td{padding:12px; border-bottom:1px solid #ddd; text-align:center;}
th{background:#f1f1f1;}
.status-select{padding:5px 8px; border-radius:5px;}
button{padding:6px 12px; background:#28a745; color:#fff; border:none; border-radius:5px; cursor:pointer;}
button:hover{background:#218838;}
</style>
</head>
<body>

<header>Admin - Manage Orders</header>
<div class="container">
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>User ID</th>
                <th>Total (₹)</th>
                <th>Payment</th>
                <th>Status</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while($order = $orders->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $order['order_id']; ?></td>
                    <td><?php echo $order['user_id']; ?></td>
                    <td>₹<?php echo $order['total_amount']; ?></td>
                    <td><?php echo $order['payment_method']; ?></td>
                    <td><?php echo $order['status']; ?></td>
                    <td><?php echo date("d-m-Y H:i", strtotime($order['created_at'])); ?></td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                            <select name="status" class="status-select">
                                <option <?php if($order['status']=="Pending") echo "selected"; ?>>Pending</option>
                                <option <?php if($order['status']=="Shipped") echo "selected"; ?>>Shipped</option>
                                <option <?php if($order['status']=="Delivered") echo "selected"; ?>>Delivered</option>
                                <option <?php if($order['status']=="Cancelled") echo "selected"; ?>>Cancelled</option>
                            </select>
                            <button type="submit" name="update_status">Update</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
