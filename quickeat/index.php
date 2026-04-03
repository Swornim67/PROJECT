<?php
// index.php - Home page showing restaurants
session_start();
require 'db.php';

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $isLoggedIn ? $_SESSION['user_name'] : 'Guest';

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
        <span>Hello, <?= htmlspecialchars($userName) ?>!</span>
        <?php if ($isLoggedIn): ?>
            <a href="orders.php">My Orders</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Sign Up</a>
        <?php endif; ?>
    </div>
</nav>

<!-- Main content -->
<div class="container">
    <h2>Available Restaurants</h2>
    <p class="subtitle">Choose a restaurant and order your food</p>

    <div class="restaurant-grid">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($r = mysqli_fetch_assoc($result)): ?>
            <div class="restaurant-card">
                <div class="restaurant-icon">🍽️</div>
                <h3><?= htmlspecialchars($r['name']) ?></h3>
                <p><?= htmlspecialchars($r['category']) ?></p>
                <p>⏱ <?= htmlspecialchars($r['delivery_time']) ?> mins delivery</p>
                <a href="menu.php?restaurant_id=<?= $r['id'] ?>" class="btn">View Menu</a>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No restaurants found.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>