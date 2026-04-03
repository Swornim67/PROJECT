<?php

session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Get all orders for this user
$result = mysqli_query($conn,
    "SELECT o.*, r.name AS restaurant_name
     FROM orders o
     JOIN restaurants r ON o.restaurant_id = r.id
     WHERE o.user_id = $user_id
     ORDER BY o.order_date DESC"
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Orders - QuickEat</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar">
    <div class="nav-logo">🍔 QuickEat</div>
    <div class="nav-links">
        <span>Hello, <?= $_SESSION['user_name'] ?>!</span>
        <a href="index.php">Home</a>
        <a href="logout.php">Logout</a>
    </div>
</nav>

<div class="container">
    <h2>My Orders</h2>

    <?php if (isset($_GET['success'])): ?>
        <p class="success">✅ Order placed successfully!</p>
    <?php endif; ?>

    <?php if (mysqli_num_rows($result) == 0): ?>
        <p class="empty-msg">You haven't placed any orders yet. <a href="index.php">Order now!</a></p>
    <?php else: ?>
        <table class="orders-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Restaurant</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($order = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= $order['id'] ?></td>
                    <td><?= $order['restaurant_name'] ?></td>
                    <td><?= $order['item_name'] ?></td>
                    <td>Rs. <?= $order['total'] ?></td>
                    <td>
                        <span class="status status-<?= strtolower(str_replace(' ', '-', $order['status'])) ?>">
                            <?= $order['status'] ?>
                        </span>
                    </td>
                    <td><?= date('M d, Y h:i A', strtotime($order['order_date'])) ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

</body>
</html>
