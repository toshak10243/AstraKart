<?php 
session_start(); 
include("includes/db.php"); 

// search wala logic
$search = "";
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $sql = "SELECT * FROM products 
            WHERE name LIKE '%$search%' 
            OR category LIKE '%$search%' 
            OR description LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM products";
}
$result = $conn->query($sql);
?>


<!DOCTYPE html> 
<html lang="en"> 
<head>   
  <meta charset="UTF-8">   
  <meta name="viewport" content="width=device-width, initial-scale=1.0">   
  <title>AstraKart - Online Shopping</title>   

  <style>     
    /* ---------------- GLOBAL ---------------- */     
    *{margin:0;padding:0;box-sizing:border-box;font-family:Arial, sans-serif;}     
    body{background:#f8f9fa;color:#333;}      
    a{text-decoration:none;color:inherit;}     
    ul{list-style:none;}      

    /* ---------------- HEADER ---------------- */     
    header{background:#2874f0;color:white;position:sticky;top:0;z-index:1000;}     
    .nav{display:flex;justify-content:space-between;align-items:center;padding:10px 20px;}     
    .logo img{height:50px;}     
    .search-bar{flex:1;margin:0 20px;}     
    .search-bar input{width:100%;padding:8px 12px;border:none;border-radius:4px;}     
    nav ul{list-style:none;margin:0;padding:0;display:flex;align-items:center;}     
    nav ul li{margin-left:20px;}     
    nav ul li a{color:#fff;font-weight:bold;}     
    nav ul li a:hover{text-decoration:underline;}     
    .cart-icon img{height:25px;}     
    .hamburger{display:none;font-size:28px;cursor:pointer;color:white;}     

    /* ---------------- SIDEMENU ---------------- */     
    .side-menu{position:fixed;top:0;left:-100%;width:250px;height:100%;background:#fff;box-shadow:2px 0 5px rgba(0,0,0,0.3);transition:0.3s;padding:20px;z-index:2000;}     
    .side-menu.active{left:0;}     
    .side-menu .close{font-size:28px;font-weight:bold;cursor:pointer;display:block;margin-bottom:20px;text-align:right;}     
    .side-menu ul li{padding:10px 0;border-bottom:1px solid #eee;}     
    .side-menu ul li a{color:#333;font-weight:500;}      

    /* ---------------- BANNER ---------------- */     
    .banner{width:100%;height:300px;background:url('uploads/banner.png') center/cover no-repeat;display:flex;align-items:center;justify-content:center;color:white;font-size:32px;font-weight:bold;}      

    /* ---------------- PRODUCTS ---------------- */     
    .products{padding:30px;}     
    .products h2{margin-bottom:20px;}     
    .product-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:20px;}     
    .card{background:#fff;padding:15px;border-radius:8px;box-shadow:0 2px 6px rgba(0,0,0,0.1);transition:0.3s;}     
    .card:hover{transform:translateY(-5px);}     
    .card img{width:100%;height:200px;object-fit:cover;border-radius:8px;}     
    .card h3{font-size:16px;margin:10px 0;}     
    .price{font-size:18px;color:#e53935;font-weight:bold;}     
    .discount{font-size:14px;color:green;margin-left:10px;}     
    .rating{color:orange;font-size:14px;}      

    /* ---------------- FOOTER ---------------- */     
    footer{background:#172337;color:white;padding:20px;text-align:center;margin-top:40px;}      

    /* ---------------- RESPONSIVE ---------------- */     
    @media(max-width:768px){       
      nav ul{display:none;}       
      .hamburger{display:block;}     
    }   
  </style> 
</head> 

<body>   

  <!-- HEADER -->   
  <header>     
    <div class="nav">       
      <div class="logo">         
        <img src="uploads/logo.png" alt="AstraKart Logo">       
      </div>       
<div class="search-bar">
  <form method="GET" action="index.php">
    <input type="text" name="search" placeholder="Search for products, brands and more..." required>
    <button type="submit" class="search-btn">
      <img src="uploads/search.png" alt="Search" />
    </button>
  </form>
  
</div>

<style>
.search-bar {
  display: flex;
  justify-content: center;
  margin: 20px auto;
  width: 100%;
  max-width: 600px; /* max size desktop par */
}

.search-bar form {
  display: flex;
  width: 100%;
  border: 2px solid #ccc;
  border-radius: 50px;
  overflow: hidden;
  background: #fff;
}

.search-bar input[type="text"] {
  flex: 1;
  padding: 12px 18px;
  border: none;
  outline: none;
  font-size: 16px;
}

.search-bar button {
  background: #ff6600; /* aap apne theme ka color rakh sakte ho */
  border: none;
  padding: 12px 18px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
}

.search-bar button img {
  width: 20px;
  height: 20px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .search-bar {
    max-width: 90%;
  }

  .search-bar input[type="text"] {
    font-size: 14px;
    padding: 10px 14px;
  }

  .search-bar button {
    padding: 10px 14px;
  }

  .search-bar button img {
    width: 18px;
    height: 18px;
  }
}

</style>

      <nav>         
        <ul id="nav-menu">           
          <li><a href="index.php">Home</a></li>           
          <li><a href="cart.php">Cart</a></li>           
          <li><a href="order_history.php">Orders</a></li>           
          <?php if(isset($_SESSION['user_id'])): ?>             
            <li>Hello, <?php echo $_SESSION['user_name']; ?> | <a href="logout.php">Logout</a></li>           
          <?php else: ?>             
            <li><a href="auth.php">Login/Sign Up</a></li>           
          <?php endif; ?>         
        </ul>         
        <div class="hamburger" id="hamburger" onclick="openMenu()">☰</div>       
      </nav>     
    </div>   
  </header>    

  <!-- SIDE MENU -->   
  <div class="side-menu" id="menu">     
    <span class="close" onclick="closeMenu()">×</span>     
    <ul>       
      <li><a href="index.php">Home</a></li>       
      <li><a href="cart.php">Cart</a></li>       
      <li><a href="order_history.php">Orders</a></li>       
      <li><a href="#">Categories</a></li>       
      <?php if(isset($_SESSION['user_id'])): ?>         
        <li><a href="logout.php">Logout</a></li>       
      <?php else: ?>         
        <li><a href="auth.php">Login/Sign Up</a></li>       
      <?php endif; ?>     
    </ul>   
  </div>    

  <!-- BANNER -->   
  <div class="banner">     
      
  </div>    

  <!-- PRODUCTS -->   
  <section class="products">     
    <h2>Featured Products</h2>     
    <div class="product-grid">       
      <?php         
        
      if($result->num_rows > 0){           
        while($row = $result->fetch_assoc()){             
          $price = $row['price'];             
          $discount = $row['discount'];             
          $final_price = $price - ($price * ($discount/100));             
          echo "               
          <div class='card'>                 
            <a href='product.php?id={$row['id']}'>
                   
              <img src='uploads/{$row['image']}' alt='{$row['name']}'>                   
              <h3>{$row['name']}</h3>                   
              <div>                     
                <span class='price'>₹".number_format($final_price,2)."</span>                     
                <span class='discount'>{$row['discount']}% off</span>                   
              </div>                   
              <div class='rating'>★★★★☆</div>                 
            </a>               
          </div>             
          ";           
        }         
      } else {           
        echo "<p>No products available.</p>";         
      }       
      ?>     
    </div>   
  </section>    

  <!-- FOOTER -->   
  <footer>     
    &copy; 2025 AstraKart. All Rights Reserved.   
  </footer>    

  <!-- JS -->   
  <script>     
    function openMenu(){ 
      document.getElementById("menu").classList.add("active"); 
      document.getElementById("hamburger").style.display="none"; 
    }     
    function closeMenu(){ 
      document.getElementById("menu").classList.remove("active"); 
      document.getElementById("hamburger").style.display="block"; 
    }   
  </script>  

</body> 
</html>
