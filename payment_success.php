<?php
session_start();
include("includes/db.php");

// Check if user is logged in
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

// Razorpay Payment ID
if(isset($_POST['razorpay_payment_id'])) {
    $razorpay_payment_id = $_POST['razorpay_payment_id'];
    $user_id = $_SESSION['user_id'];
    $cart = $_SESSION['cart'];

    // Calculate total amount
    $subtotal = 0;
    foreach($cart as $item){
        $item_total = $item['price'] * $item['quantity'];
        $discount_amt = ($item['discount']/100) * $item_total;
        $subtotal += ($item_total - $discount_amt);
    }

    $gst = $subtotal * 0.18; // 18% GST
    $grand_total = $subtotal + $gst;

    // Generate random Order ID
    $order_id = strtoupper("ORD".rand(10000,99999));

    // Save order in 'orders' table
    $stmt = $conn->prepare("INSERT INTO orders (order_id, user_id, total_amount, payment_method, payment_id, status, created_at) VALUES (?, ?, ?, ?, ?, 'Pending', NOW())");
    $payment_method = "Razorpay";
    $stmt->bind_param("sidsi", $order_id, $user_id, $grand_total, $payment_method, $razorpay_payment_id);
    $stmt->execute();
    $stmt->close();

    // Save order items
    foreach($cart as $item){
        $stmt2 = $conn->prepare("INSERT INTO order_items (order_id, product_id, product_name, quantity, price) VALUES (?, ?, ?, ?, ?)");
        $stmt2->bind_param("siidi", $order_id, $item['id'], $item['name'], $item['quantity'], $item['price']);
        $stmt2->execute();
        $stmt2->close();
    }

    // Clear cart
    unset($_SESSION['cart']);

    // Redirect to Order Success page
    header("Location: order_success.php?order_id=".$order_id);
    exit;
} else {
    echo "<h2>Payment Failed or Invalid Request!</h2>";
}
?>
