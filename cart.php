<?php
session_start();

// Agar cart session exist nahi karta to initialize karo
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Product remove karna
if (isset($_GET['remove'])) {
    $id = $_GET['remove'];
    unset($_SESSION['cart'][$id]);
}

// Quantity update karna
if (isset($_POST['update'])) {
    foreach ($_POST['qty'] as $id => $qty) {
        if ($qty > 0) {
            $_SESSION['cart'][$id]['quantity'] = $qty;
        } else {
            unset($_SESSION['cart'][$id]); // agar 0 kar diya to remove
        }
    }
}

// Dummy discount & GST
$gst_rate = 18; // 18% GST
$discount_rate = 10; // 10% discount

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AstraKart | Shopping Cart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin:0; padding:0;
            background: #f9f9f9;
        }
        header {
            background: #2874f0;
            padding: 15px;
            color: #fff;
            font-size: 22px;
            text-align: center;
        }
        .container {
            width: 90%;
            margin: 20px auto;
        }
        table {
            width: 100%;
            background: #fff;
            border-collapse: collapse;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        table th, table td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background: #f1f1f1;
        }
        img {
            width: 80px;
            height: auto;
            border-radius: 8px;
        }
        .btn {
            padding: 8px 14px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .remove {
            background: #e74c3c;
            color: #fff;
        }
        .update {
            background: #27ae60;
            color: #fff;
        }
        .checkout {
            margin-top: 20px;
            float: right;
            background: #ff9800;
            color: #fff;
            font-size: 18px;
        }
        .totals {
            margin-top: 20px;
            float: right;
            background: #fff;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        @media(max-width:768px) {
            table, thead, tbody, th, td, tr {
                display: block;
                width: 100%;
            }
            td {
                text-align: right;
                padding-left: 50%;
                position: relative;
            }
            td:before {
                position: absolute;
                left: 15px;
                width: 45%;
                text-align: left;
                font-weight: bold;
            }
            td:nth-of-type(1):before { content: "Product"; }
            td:nth-of-type(2):before { content: "Price"; }
            td:nth-of-type(3):before { content: "Qty"; }
            td:nth-of-type(4):before { content: "Total"; }
            td:nth-of-type(5):before { content: "Action"; }
        }
    </style>
</head>
<body>
<header>
     AstraKart - Your Shopping Cart
</header>

<div class="container">
    <form method="post">
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price (₹)</th>
                    <th>Quantity</th>
                    <th>Total (₹)</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php 
            $subtotal = 0;
            if (!empty($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $id => $item) {
                    $total = $item['price'] * $item['quantity'];
                    $subtotal += $total;
                    echo "<tr>
                        <td><img src='{$item['image']}' alt=''> <br> {$item['name']}</td>
                        <td>{$item['price']}</td>
                        <td><input type='number' name='qty[$id]' value='{$item['quantity']}' min='1' style='width:50px;'></td>
                        <td>{$total}</td>
                        <td><a href='cart.php?remove=$id' class='btn remove'>Remove</a></td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Your cart is empty.</td></tr>";
            }
            ?>
            </tbody>
        </table>
        <br>
        <button type="submit" name="update" class="btn update">Update Cart</button>
    </form>

    <?php if (!empty($_SESSION['cart'])): 
        $discount = ($subtotal * $discount_rate) / 100;
        $gst = ($subtotal * $gst_rate) / 100;
        $grand_total = $subtotal - $discount + $gst;
    ?>
    <div class="totals">
        <p>Subtotal: ₹<?php echo $subtotal; ?></p>
        <p>Discount (<?php echo $discount_rate; ?>%): -₹<?php echo $discount; ?></p>
        <p>GST (<?php echo $gst_rate; ?>%): +₹<?php echo $gst; ?></p>
        <hr>
        <h3>Grand Total: ₹<?php echo $grand_total; ?></h3>
        <!--button class="btn checkout">Proceed to Checkout</button-->
		<a href="checkout.php" class="btn checkout">Proceed to Checkout</a>

    </div>
    <?php endif; ?>
</div>
</body>
</html>
