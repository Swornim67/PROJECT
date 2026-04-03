<?php

session_start();
require 'db.php';

$error   = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $password = $_POST['password'];

    // Check if email already exists
    $check = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");
    if (mysqli_num_rows($check) > 0) {
        $error = "Email already registered!";
    } else {
        $sql = "INSERT INTO users (name, email, password, role) VALUES ('$name','$email','$password','customer')";
        if (mysqli_query($conn, $sql)) {
            $success = "Account created! You can now login.";
        } else {
            $error = "Something went wrong. Try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - QuickEat</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="auth-page">

<div class="auth-box">
    <h1>🍔 QuickEat</h1>
    <h2>Create Account</h2>

    <?php if ($error): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <?php if ($success): ?>
        <p class="success"><?= $success ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Full Name</label>
        <input type="text" name="name" placeholder="Your full name" required>

        <label>Email</label>
        <input type="email" name="email" placeholder="Your email" required>

        <label>Password</label>
        <input type="password" name="password" placeholder="Choose a password" required>

        <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="login.php">Login here</a></p>
</div>

</body>
</html>
