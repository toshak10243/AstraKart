<?php
session_start();
include 'db.php'; // apna DB connection file

// Agar cart empty hai to redirect
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: index.php");
    exit;
}

// Agar user login nahi hai to redirect
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id']; // ✅ Ab session se user_id lo

// Random order ID generate
$orderID = strtoupper("ORD" . rand(10000, 99999));

// Order total calculate
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $subtotal = $item['price'] * $item['quantity'];
    $total += $subtotal;
}

// GST (18%) + Discount (10%)
$gst = $total * 0.18;
$discount = $total * 0.10;
$finalTotal = $total + $gst - $discount;

// Random order status list
$statuses = ["Pending", "Processing", "Shipped", "Delivered"];
$status = $statuses[array_rand($statuses)];

// Status ke liye colors
$statusColors = [
    "Pending"    => "#ffc107", // Yellow
    "Processing" => "#17a2b8", // Cyan
    "Shipped"    => "#007bff", // Blue
    "Delivered"  => "#28a745"  // Green
];
$statusColor = $statusColors[$status];

// Insert into `orders`
$conn->query("INSERT INTO orders (order_id, user_id, total_amount, status)  
VALUES ('$orderID', '$user_id', '$finalTotal', '$status')");

// Insert into `order_items`
foreach ($_SESSION['cart'] as $item) {
    $product_id = $item['id'];   // ✅ ab yeh available hai
    $product_name = $item['name'];
    $price = $item['price'];
    $qty = $item['quantity'];

    $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, product_name, price, quantity) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sissd", $orderID, $product_id, $product_name, $price, $qty);
    $stmt->execute();
}

// ✅ Ab cart clear karo
unset($_SESSION['cart']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order Success - AstraKart</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f8f9fa;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .success-container {
      background: #fff;
      padding: 40px;
      border-radius: 12px;
      text-align: center;
      box-shadow: 0 5px 15px rgba(0,0,0,0.2);
      max-width: 500px;
      width: 90%;
    }
    .success-container h1 { color: #28a745; }
    .order-id { font-weight: bold; color: #007bff; }
    .btn {
      display: inline-block;
      margin-top: 20px;
      padding: 12px 20px;
      background: #007bff;
      color: #fff;
      text-decoration: none;
      border-radius: 6px;
      font-weight: bold;
    }
    .btn:hover { background: #0056b3; }
    .status-badge {
      display: inline-block;
      padding: 6px 12px;
      border-radius: 20px;
      font-weight: bold;
      color: #fff;
    }
  </style>
</head>
<body>
  <div class="success-container">
    <div style="font-size:50px;color:#28a745;">✔</div>
    <h1>Order Placed Successfully!</h1>
    <p>Thank you for shopping with <strong>AstraKart</strong>.</p>
    <p class="order-id">Your Order ID: <?php echo $orderID; ?></p>

    <div class="summary-box" style="margin-top:20px;text-align:left;border:1px solid #ddd;padding:15px;border-radius:8px;background:#f9f9f9;">
      <h3>Order Summary:</h3>
      <p><strong>Items Total:</strong> ₹<?php echo number_format($total, 2); ?></p>
      <p><strong>GST (18%):</strong> ₹<?php echo number_format($gst, 2); ?></p>
      <p><strong>Discount (10%):</strong> -₹<?php echo number_format($discount, 2); ?></p>
      <p><strong>Final Amount:</strong> ₹<?php echo number_format($finalTotal, 2); ?></p>
      <p><strong>Order Status:</strong> 
        <span class="status-badge" style="background: <?php echo $statusColor; ?>;">
          <?php echo $status; ?>
        </span>
      </p>
    </div>

    <a href="index.php" class="btn">Continue Shopping</a>
  </div>
</body>
</html>
