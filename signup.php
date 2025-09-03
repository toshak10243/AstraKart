<?php
include("includes/db.php");

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $phone    = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $sql = "INSERT INTO users (name, email, phone, password) VALUES ('$name','$email','$phone','$password')";
    if ($conn->query($sql) === TRUE) {
        $message = "Signup successful! <a href='login.php'>Login Now</a>";
    } else {
        $message = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>AstraKart - Signup</title>
    <style>
        body { font-family: Arial; background:#f2f2f2; }
        .container { width:400px; margin:50px auto; padding:20px; background:#fff; border-radius:10px; }
        h2 { text-align:center; }
        input { width:100%; padding:10px; margin:10px 0; border:1px solid #ccc; border-radius:5px; }
        button { width:100%; padding:10px; background:#4CAF50; color:white; border:none; border-radius:5px; cursor:pointer; }
        button:hover { background:#45a049; }
        .msg { color:green; text-align:center; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Create Account</h2>
        <form method="POST" onsubmit="return validateForm()">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="phone" placeholder="Phone" required>
            <input type="password" id="pass" name="password" placeholder="Password" required>
            <input type="password" id="cpass" placeholder="Confirm Password" required>
            <button type="submit">Signup</button>
        </form>
        <p class="msg"><?php echo $message; ?></p>
    </div>

    <script>
        function validateForm(){
            let p = document.getElementById("pass").value;
            let cp = document.getElementById("cpass").value;
            if(p !== cp){
                alert("Passwords do not match!");
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
