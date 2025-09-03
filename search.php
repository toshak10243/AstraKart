<!-- search.php -->
<?php include 'db.php'; ?>

<!-- Search Form -->
<form method="GET" action="search.php">
    <input type="text" name="search" placeholder="Search products..." 
           value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
    <button type="submit">Search</button>
</form>

<hr>

<?php
$search = "";
if (isset($_GET['search']) && $_GET['search'] != "") {
    $search = mysqli_real_escape_string($conn, $_GET['search']);

    $query = "SELECT * FROM products 
              WHERE name LIKE '%$search%' 
              OR brand LIKE '%$search%' 
              OR category LIKE '%$search%'";

    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        echo "<h2>Search Results for '<b>$search</b>'</h2>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='product'>";
            echo "<img src='uploads/" . $row['image'] . "' alt='" . $row['name'] . "' style='width:100px;height:100px'>";
            echo "<h3>" . $row['name'] . "</h3>";
            echo "<p>Brand: " . $row['brand'] . "</p>";
            echo "<p>₹" . $row['price'] . "</p>";
            echo "</div><hr>";
        }
    } else {
        echo "<p>No products found for '<b>$search</b>'</p>";
    }
}
?>
