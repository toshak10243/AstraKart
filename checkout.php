<?php
session_start();
include 'db.php';

// Agar cart set nahi hai to empty kar do
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Subtotal calculation
$subtotal = 0;

foreach ($cart as $item) {
    // Qty and discount safe values le lo (agar null ho to default set karo)
    $qty = isset($item['qty']) ? (int)$item['qty'] : 1;
    $discount = isset($item['discount']) ? (float)$item['discount'] : 0;

    $itemTotal = $item['price'] * $qty;
    $discountAmount = ($discount / 100) * $itemTotal;
    $subtotal += ($itemTotal - $discountAmount);
}

$gst = 0.18 * $subtotal;
$grandTotal = $subtotal + $gst;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checkout</title>
  <style>
    * {margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif;}

    body {background: #f4f4f4;}

    .checkout-container {
      max-width: 1100px;
      margin: 30px auto;
      display: grid;
      grid-template-columns: 2fr 1fr;
      gap: 20px;
      padding: 20px;
    }

    .form-section, .summary-section {
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .form-section h2, .summary-section h2 {margin-bottom: 15px; color: #333;}

    label {display: block; margin: 10px 0 5px;}
    input, select, textarea {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }

    .payment-methods {
      display: flex;
      flex-direction: column;
      gap: 8px;
      margin-bottom: 20px;
    }

    .order-items {
      margin-bottom: 20px;
    }
    .order-items div {
      display: flex;
      justify-content: space-between;
      margin: 8px 0;
      border-bottom: 1px solid #eee;
      padding-bottom: 5px;
    }

    .total {
      font-weight: bold;
      font-size: 18px;
      margin-top: 10px;
      color: #d32f2f;
    }

    .btn {
      width: 100%;
      padding: 12px;
      background: #ff6f00;
      color: #fff;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      transition: 0.3s;
    }
    .btn:hover {background: #e65100;}

    @media (max-width: 768px) {
      .checkout-container {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>
  <div class="checkout-container">
    
    <!-- Address + Payment -->
    <div class="form-section">
      <h2>Delivery Address</h2>
      <form method="post" action="order_success.php">
        <label>Full Name</label>
        <input type="text" name="fullname" required>

        <label>Phone Number</label>
        <input type="text" name="phone" required>

        <label>Address</label>
        <textarea name="address" rows="3" required></textarea>

        <label>City</label>
        <input type="text" name="city" required>

        <label>Pincode</label>
        <input type="text" name="pincode" required>

        <label>State</label>
        <select name="state" required>
          <option value="">-- Select State --</option>
          <option>Rajasthan</option>
          <option>Delhi</option>
          <option>Maharashtra</option>
          <option>Karnataka</option>
          <option>Uttar Pradesh</option>
        </select>

        <h2>Payment Method</h2>
        <div class="payment-methods">
          <label><input type="radio" name="payment" value="COD" required> Cash on Delivery</label>
          <label><input type="radio" name="payment" value="UPI"> UPI</label>
          <label><input type="radio" name="payment" value="Card"> Credit/Debit Card</label>
          <label><input type="radio" name="payment" value="NetBanking"> Net Banking</label>
        </div>

        <button type="submit" class="btn">Place Order</button>
      </form>
    </div>

    <!-- Order Summary -->
    <div class="summary-section">
      <h2>Order Summary</h2>
      <div class="order-items">
  <?php foreach($cart as $item): ?>
    <?php 
      $qty = isset($item['qty']) ? (int)$item['qty'] : 1;
      $discount = isset($item['discount']) ? (float)$item['discount'] : 0;
      $itemTotal = $item['price'] * $qty;
      $discountAmount = ($discount / 100) * $itemTotal;
      $finalPrice = $itemTotal - $discountAmount;
    ?>
    <div>
      <span><?= htmlspecialchars($item['name']) ?> x <?= $qty ?></span>
      <span>₹<?= number_format($finalPrice, 2) ?></span>
    </div>
  <?php endforeach; ?>
</div>

      <div><strong>Subtotal:</strong> ₹<?= number_format($subtotal,2) ?></div>
      <div><strong>GST (18%):</strong> ₹<?= number_format($gst,2) ?></div>
      <div class="total">Total Payable: ₹<?= number_format($grandTotal,2) ?></div>
    </div>
  </div>
</body>
</html>
