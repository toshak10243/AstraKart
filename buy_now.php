<?php
session_start();
include 'db.php';

// Check product id
if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);

    // Check if product exists
    $result = $conn->query("SELECT * FROM products WHERE id = $product_id");
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();

        // Add product to cart session
        $_SESSION['cart'][$product_id] = [
            "id" => $product['id'],
            "name" => $product['name'],
            "price" => $product['price'],
            "quantity" => 1
        ];

        // Redirect to checkout page
        header("Location: checkout.php");
        exit;
    } else {
        echo "Product not found!";
    }
} else {
    echo "Invalid request!";
}
