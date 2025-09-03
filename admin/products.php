<?php
session_start();
include("../includes/db.php");

if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit;
}

// Add Product
if(isset($_POST['add_product'])){
    $name = $_POST['name'];
    $price = $_POST['price'];
    $discount = $_POST['discount'];
    $description = $_POST['description'];

    // Upload image
    $image = $_FILES['image']['name'];
    $target = "../uploads/".basename($image);
    move_uploaded_file($_FILES['image']['tmp_name'], $target);

    $conn->query("INSERT INTO products (name, price, discount, description, image) VALUES ('$name','$price','$discount','$description','$image')");
}

// Fetch products
$products = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Products - AstraKart</title>
<style>
body{font-family:Arial,sans-serif; margin:0; background:#f4f4f4;}
header{background:#2874f0; color:#fff; padding:15px; text-align:center; font-size:22px;}
.container{max-width:1100px; margin:20px auto; padding:0 20px;}
table{width:100%; border-collapse: collapse; background:#fff; box-shadow:0 2px 6px rgba(0,0,0,0.1);}
th, td{padding:12px; border-bottom:1px solid #ddd; text-align:center;}
th{background:#f1f1f1;}
form{background:#fff; padding:20px; border-radius:10px; box-shadow:0 2px 6px rgba(0,0,0,0.1); margin-bottom:20px;}
input, textarea{width:100%; padding:10px; margin-bottom:10px; border:1px solid #ccc; border-radius:6px;}
button{padding:10px 15px; background:#ff6f00; color:#fff; border:none; border-radius:6px; cursor:pointer;}
button:hover{background:#e65100;}
img{width:80px; height:auto; border-radius:6px;}
</style>
</head>
<body>
<header>Admin - Manage Products</header>

<div class="container">
    <form method="post" enctype="multipart/form-data">
        <h3>Add New Product</h3>
        <input type="text" name="name" placeholder="Product Name" required>
        <input type="number" name="price" placeholder="Price" required>
        <input type="number" name="discount" placeholder="Discount (%)" required>
        <textarea name="description" placeholder="Product Description" rows="3" required></textarea>
        <input type="file" name="image" required>
        <button type="submit" name="add_product">Add Product</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Discount</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($p = $products->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $p['id']; ?></td>
                    <td><?php echo $p['name']; ?></td>
                    <td>₹<?php echo $p['price']; ?></td>
                    <td><?php echo $p['discount']; ?>%</td>
                    <td><img src="../uploads/<?php echo $p['image']; ?>" alt=""></td>
                    <td>
                        <a href="products.php?edit=<?php echo $p['id']; ?>">Edit</a> | 
                        <a href="products.php?delete=<?php echo $p['id']; ?>">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
