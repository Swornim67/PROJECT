<?php
// login.php
session_start();
require 'db.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    $sql    = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $sql);
    $user   = mysqli_fetch_assoc($result);

    if ($user) {
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];
        header("Location: index.php");
        exit;
    } else {
        $error = "Wrong email or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - QuickEat</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="auth-page">

<div class="auth-box">
    <h1>🍔 QuickEat</h1>
    <h2>Login</h2>

    <?php if ($error): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Email</label>
        <input type="email" name="email" placeholder="Enter your email" required>

        <label>Password</label>
        <input type="password" name="password" placeholder="Enter your password" required>

        <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="register.php">Register here</a></p>
</div>

</body>
</html>
