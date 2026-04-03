<?php

session_start();
require 'db.php';

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Get all restaurants from DB
$result = mysqli_query($conn, "SELECT * FROM restaurants");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>QuickEat - Home</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar">
    <div class="nav-logo">🍔 QuickEat</div>
    <div class="nav-links">
        <span>Hello, <?= $_SESSION['user_name'] ?>!</span>
        <a href="orders.php">My Orders</a>
        <a href="logout.php">Logout</a>
    </div>
</nav>

<!-- Main content -->
<div class="container">
    <h2>Available Restaurants</h2>
    <p class="subtitle">Choose a restaurant and order your food</p>

    <div class="restaurant-grid">
        <?php while ($r = mysqli_fetch_assoc($result)): ?>
        <div class="restaurant-card">
            <div class="restaurant-icon">🍽️</div>
            <h3><?= $r['name'] ?></h3>
            <p><?= $r['category'] ?></p>
            <p>⏱ <?= $r['delivery_time'] ?> mins delivery</p>
            <a href="menu.php?restaurant_id=<?= $r['id'] ?>" class="btn">View Menu</a>
        </div>
        <?php endwhile; ?>
    </div>
</div>

</body>
</html>
