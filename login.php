<?php
include("includes/db.php");
session_start();

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['name']    = $row['name'];
            header("Location: index.php");
        } else {
            $message = "Invalid password!";
        }
    } else {
        $message = "User not found!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>AstraKart - Login</title>
    <style>
        body { font-family: Arial; background:#f2f2f2; }
        .container { width:400px; margin:50px auto; padding:20px; background:#fff; border-radius:10px; }
        h2 { text-align:center; }
        input { width:100%; padding:10px; margin:10px 0; border:1px solid #ccc; border-radius:5px; }
        button { width:100%; padding:10px; background:#2196F3; color:white; border:none; border-radius:5px; cursor:pointer; }
        button:hover { background:#0b7dda; }
        .msg { color:red; text-align:center; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p class="msg"><?php echo $message; ?></p>
    </div>
</body>
</html>
