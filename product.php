<?php 
include("includes/db.php"); 
session_start();  

if(!isset($_GET['id'])){
    die("Product not found!");
}
$product_id = $_GET['id'];

// Fetch product (id column use karna hai, product_id nahi)
$sql = "SELECT * FROM products WHERE id='$product_id'";
$result = $conn->query($sql);

if($result->num_rows == 0){
    die("Product not found!");
}
$product = $result->fetch_assoc();

// Price calculation
$base_price   = $product['price'];
$discount     = $product['discount'];
$gst          = isset($product['gst']) ? $product['gst'] : 0; // agar gst column hai to
$discount_amt = $base_price * ($discount/100);
$price_after_discount = $base_price - $discount_amt;
$gst_amt      = $price_after_discount * ($gst/100);
$final_price  = $price_after_discount + $gst_amt;

// ---------------------- ADD TO CART LOGIC ----------------------
if (isset($_POST['add_to_cart'])) {
    $cart_item = [
	    "id" => $row['id'],
        'name' => $product['name'],
        'price' => $final_price, // discount + gst apply karke price
        'image' => "uploads/".$product['image'],
        'quantity' => 1
    ];

    // Agar already cart me hai to quantity badhao
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += 1;
    } else {
        $_SESSION['cart'][$product_id] = $cart_item;
    }

    // Add karne ke baad cart.php par redirect
    header("Location: cart.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $product['name']; ?> - AstraKart</title>
  <style>
    body{font-family:Arial,sans-serif;background:#f8f9fa;margin:0;padding:0;}
    .container{max-width:1200px;margin:auto;padding:20px;display:flex;flex-wrap:wrap;gap:30px;}
    .images{flex:1;min-width:300px;}
    .images img{width:100%;height:350px;object-fit:cover;border-radius:8px;display:none;}
    .images img.active{display:block;}
    .dots{text-align:center;margin-top:10px;}
    .dots span{height:12px;width:12px;margin:0 5px;background:#ccc;border-radius:50%;display:inline-block;cursor:pointer;}
    .dots .active{background:#2874f0;}
    .details{flex:2;min-width:300px;background:#fff;padding:20px;border-radius:8px;box-shadow:0 2px 6px rgba(0,0,0,0.1);}
    h2{margin-bottom:10px;}
    .price{font-size:22px;color:#e53935;font-weight:bold;}
    .old-price{text-decoration:line-through;color:#777;margin-left:10px;}
    .discount{color:green;margin-left:10px;}
    .gst{font-size:14px;color:#555;}
    .rating{color:orange;font-size:16px;margin:10px 0;}
    .desc{margin:15px 0;}
    .btns{display:flex;gap:15px;margin-top:20px;}
    .btns button{flex:1;padding:14px;border:none;font-size:16px;border-radius:5px;cursor:pointer;transition:0.3s;}
    .btn-cart{background:#ff9f00;color:white;}
    .btn-cart:hover{background:#fb8c00;}
    .btn-buy{background:#fb641b;color:white;}
    .btn-buy:hover{background:#e65100;}
    .reviews{margin-top:30px;background:#fff;padding:20px;border-radius:8px;box-shadow:0 2px 6px rgba(0,0,0,0.1);}
    .review{border-bottom:1px solid #eee;padding:10px 0;}
    .review:last-child{border:none;}
    .review strong{color:#2874f0;}
    @media(max-width:768px){.container{flex-direction:column;}}
  </style>
</head>
<body>
  <div class="container">
    <!-- Product Images -->
    <div class="images">
      <img src="uploads/<?php echo $product['image']; ?>" class="active">
      <?php if(!empty($product['image2'])): ?><img src="uploads/<?php echo $product['image2']; ?>"><?php endif; ?>
      <?php if(!empty($product['image3'])): ?><img src="uploads/<?php echo $product['image3']; ?>"><?php endif; ?>
      <?php if(!empty($product['image4'])): ?><img src="uploads/<?php echo $product['image4']; ?>"><?php endif; ?>

      <div class="dots">
        <span class="active" onclick="showImage(0)"></span>
        <?php if(!empty($product['image2'])): ?><span onclick="showImage(1)"></span><?php endif; ?>
        <?php if(!empty($product['image3'])): ?><span onclick="showImage(2)"></span><?php endif; ?>
        <?php if(!empty($product['image4'])): ?><span onclick="showImage(3)"></span><?php endif; ?>
      </div>
    </div>

    <!-- Product Details -->
    <div class="details">
      <h2><?php echo $product['name']; ?></h2>
      <div class="price">
        ₹<?php echo number_format($final_price,2); ?>
        <span class="old-price">₹<?php echo number_format($base_price,2); ?></span>
        <span class="discount"><?php echo $discount; ?>% OFF</span>
      </div>
      <div class="gst">Including GST (<?php echo $gst; ?>%)</div>
      <div class="rating">★★★★☆ (4.0)</div>
      <p class="desc"><?php echo $product['description']; ?></p>

      <div class="btns">
        <form method="POST" style="flex:1;">
          <button type="submit" name="add_to_cart" class="btn-cart">Add to Cart</button>
        </form>
        <!--button class="btn-buy">Buy Now</button-->
		<a href="checkout.php" class="btn btn-primary">Buy Now</a>
		<style>
		.btn {
  display: inline-block;
  padding: 12px 24px;
  font-size: 16px;
  font-weight: 600;
  border-radius: 8px;
  text-align: center;
  cursor: pointer;
  transition: all 0.3s ease-in-out;
  text-decoration: none;
  border: none;
}

.btn-primary {
  background: linear-gradient(135deg, #007bff, #0056b3);
  color: #fff;
  box-shadow: 0 4px 8px rgba(0, 123, 255, 0.4);
}

.btn-primary:hover {
  background: linear-gradient(135deg, #0056b3, #004085);
  transform: translateY(-2px);
  box-shadow: 0 6px 12px rgba(0, 86, 179, 0.5);
}

.btn-success {
  background: linear-gradient(135deg, #28a745, #1e7e34);
  color: #fff;
  box-shadow: 0 4px 8px rgba(40, 167, 69, 0.4);
}

.btn-success:hover {
  background: linear-gradient(135deg, #1e7e34, #155d27);
  transform: translateY(-2px);
  box-shadow: 0 6px 12px rgba(30, 126, 52, 0.5);
}

</style>

      </div>
    </div>
  </div>

  <!-- Reviews -->
  <div class="container">
    <div class="reviews">
      <h3>Customer Reviews</h3>
      <?php
        $sql_reviews = "SELECT r.*, u.name FROM reviews r JOIN users u ON r.user_id=u.id WHERE r.product_id='$product_id' ORDER BY r.created_at DESC";
        $result_reviews = $conn->query($sql_reviews);

        if($result_reviews && $result_reviews->num_rows > 0){
          while($rev = $result_reviews->fetch_assoc()){
            echo "
              <div class='review'>
                <strong>{$rev['name']}</strong> ⭐ {$rev['rating']} <br>
                <p>{$rev['comment']}</p>
              </div>
            ";
          }
        } else {
          echo "<p>No reviews yet. Be the first to review!</p>";
        }
      ?>
    </div>
  </div>

  <script>
    let images = document.querySelectorAll(".images img");
    let dots = document.querySelectorAll(".dots span");
    function showImage(index){
      images.forEach((img)=> img.classList.remove("active"));
      dots.forEach((dot)=> dot.classList.remove("active"));
      images[index].classList.add("active");
      dots[index].classList.add("active");
    }
  </script>
</body>
</html>
