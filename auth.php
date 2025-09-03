<?php
session_start();
include("includes/db.php");

// Handle Login
if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $res = $conn->query("SELECT * FROM users WHERE email='$email'");
    if($res->num_rows>0){
        $user = $res->fetch_assoc();
        if(password_verify($password,$user['password'])){
            $_SESSION['user_id']=$user['id'];
            $_SESSION['user_name']=$user['name'];
            header("Location:index.php");
            exit;
        }else{
            $login_error="Invalid Password!";
        }
    }else{
        $login_error="User not found!";
    }
}

// Handle Signup
if(isset($_POST['signup'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $conn->query("INSERT INTO users (name,email,password) VALUES ('$name','$email','$password')");
    echo "<script>alert('Thank you for registering! Please login now.'); window.location='auth.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login/Sign Up - AstraKart</title>
<style>
body{font-family:Arial,sans-serif;margin:0;padding:0;background:#f4f4f4;}
.container{max-width:400px;margin:50px auto;background:#fff;padding:30px;border-radius:10px;box-shadow:0 4px 10px rgba(0,0,0,0.1);}
h2{text-align:center;color:#2874f0;}
input{width:100%;padding:10px;margin:10px 0;border-radius:6px;border:1px solid #ccc;}
button{width:100%;padding:10px;background:#2874f0;color:#fff;border:none;border-radius:6px;cursor:pointer;}
button:hover{background:#1a5fc1;}
.toggle{color:#2874f0;cursor:pointer;text-align:center;margin-top:10px;}
.error{color:red;text-align:center;}
</style>
</head>
<body>

<div class="container" id="login-form">
    <h2>Login</h2>
    <?php if(isset($login_error)) echo "<p class='error'>$login_error</p>"; ?>
    <form method="post">
        <input type="email" name="email" placeholder="Enter email" required>
        <input type="password" name="password" placeholder="Enter password" required>
        <button type="submit" name="login">Login</button>
    </form>
    <p class="toggle" onclick="showSignup()">New at AstraKart? Create new account here</p>
</div>

<div class="container" id="signup-form" style="display:none;">
    <h2>Sign Up</h2>
    <form method="post">
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="cpassword" placeholder="Confirm Password" required>
        <button type="submit" name="signup">Register</button>
    </form>
    <p class="toggle" onclick="showLogin()">Already have an account? Login here</p>
</div>

<script>
function showSignup(){
    document.getElementById('login-form').style.display='none';
    document.getElementById('signup-form').style.display='block';
}
function showLogin(){
    document.getElementById('signup-form').style.display='none';
    document.getElementById('login-form').style.display='block';
}
</script>

</body>
</html>
