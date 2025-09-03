<?php 
session_start(); 
include("includes/db.php");  

// Check if user logged in
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user orders
$sql_orders = "SELECT * FROM orders WHERE user_id='$user_id' ORDER BY created_at DESC";
$result_orders = $conn->query($sql_orders);
?> 

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Orders - AstraKart</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f4f4;
      margin: 0;
      padding: 0;
    }
    .container {
      max-width: 1100px;
      margin: 30px auto;
      padding: 20px;
    }
    h2 {
      color: #2874f0;
      margin-bottom: 20px;
      text-align: center;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      background: #fff;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      border-radius: 8px;
      overflow: hidden;
    }
    th, td {
      padding: 12px;
      text-align: center;
      border-bottom: 1px solid #ddd;
    }
    th {
      background: #2874f0;
      color: #fff;
      font-weight: 600;
    }
    .status {
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 14px;
      font-weight: bold;
      display: inline-block;
      min-width: 90px;
    }
    .Pending { background: #ff9800; color: #fff; }
    .Processing { background: #2196f3; color: #fff; }
    .Shipped { background: #673ab7; color: #fff; }
    .Delivered { background: #4caf50; color: #fff; }
    .Cancelled { background: #e53935; color: #fff; }

    @media(max-width:768px){
      table, thead, tbody, th, td, tr {display:block; width:100%;}
      thead {display:none;}
      tr {
        margin-bottom:15px;
        background:#fff;
        border-radius:8px;
        padding:10px;
        box-shadow:0 2px 4px rgba(0,0,0,0.1);
      }
      td {
        text-align:right;
        padding-left:50%;
        position:relative;
        border:none;
      }
      td:before {
        position:absolute;
        left:15px;
        width:45%;
        text-align:left;
        font-weight:bold;
        color:#555;
      }
      td:nth-of-type(1):before {content:"Order ID";}
      td:nth-of-type(2):before {content:"Date";}
      td:nth-of-type(3):before {content:"Items";}
      td:nth-of-type(4):before {content:"Total";}
      td:nth-of-type(5):before {content:"Payment";}
      td:nth-of-type(6):before {content:"Status";}
    }
  </style>
</head>
<body>

<div class="container">
  <h2>My Orders</h2>
  <table>
    <thead>
      <tr>
        <th>Order ID</th>
        <th>Date</th>
        <th>Items</th>
        <th>Total (₹)</th>
        <th>Payment</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if($result_orders->num_rows > 0){
          while($order = $result_orders->fetch_assoc()){
              // Fetch items for this order
              $order_id = $order['order_id'];
              $sql_items = "SELECT * FROM order_items WHERE order_id='$order_id'";
              $res_items = $conn->query($sql_items);

              $items_list = "";
              while($item = $res_items->fetch_assoc()){
                  $items_list .= $item['product_name'] . " x " . $item['quantity'] . "<br>";
              }

              // ✅ Payment method random assign if empty
              $payment = $order['payment_method'];
              if(empty($payment)){
                  $methods = ["Cash on Delivery", "UPI", "Credit/Debit Card", "Net Banking"];
                  $payment = $methods[array_rand($methods)];
              }

              echo "<tr>
                <td>{$order['order_id']}</td>
                <td>".date("d-m-Y H:i", strtotime($order['created_at']))."</td>
                <td>{$items_list}</td>
                <td>₹{$order['total_amount']}</td>
                <td>{$payment}</td>
                <td><span class='status {$order['status']}'>{$order['status']}</span></td>
              </tr>";
          }
      } else {
          echo "<tr><td colspan='6'>You have no orders yet.</td></tr>";
      }
      ?>
    </tbody>
  </table>
</div>

</body>
</html>
