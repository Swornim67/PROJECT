<?php

session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['order_id'])) {
    $order_id  = $_POST['order_id'];
    $new_status = $_POST['status'];
    mysqli_query($conn, "UPDATE orders SET status='$new_status' WHERE id=$order_id");
    header("Location: admin.php");
    exit;
}

// Get all orders with user and restaurant info
$result = mysqli_query($conn,
    "SELECT o.*, u.name AS customer_name, r.name AS restaurant_name
     FROM orders o
     JOIN users u ON o.user_id = u.id
     JOIN restaurants r ON o.restaurant_id = r.id
     ORDER BY o.order_date DESC"
);

// Stats
$total_orders   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM orders"))['c'];
$total_users    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM users"))['c'];
$total_revenue  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total) AS s FROM orders WHERE status='Delivered'"))['s'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - QuickEat</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar">
    <div class="nav-logo">🍔 QuickEat Admin</div>
    <div class="nav-links">
        <a href="index.php">View Site</a>
        <a href="logout.php">Logout</a>
    </div>
</nav>

<div class="container">
    <h2>Admin Dashboard</h2>

    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <h3><?= $total_orders ?></h3>
            <p>Total Orders</p>
        </div>
        <div class="stat-card">
            <h3><?= $total_users ?></h3>
            <p>Registered Users</p>
        </div>
        <div class="stat-card">
            <h3>Rs. <?= number_format($total_revenue, 2) ?></h3>
            <p>Revenue (Delivered)</p>
        </div>
    </div>

    <h3>All Orders</h3>
    <table class="orders-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Customer</th>
                <th>Restaurant</th>
                <th>Items</th>
                <th>Total</th>
                <th>Status</th>
                <th>Update</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($order = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $order['id'] ?></td>
                <td><?= $order['customer_name'] ?></td>
                <td><?= $order['restaurant_name'] ?></td>
                <td><?= $order['item_name'] ?></td>
                <td>Rs. <?= $order['total'] ?></td>
                <td>
                    <span class="status status-<?= strtolower(str_replace(' ', '-', $order['status'])) ?>">
                        <?= $order['status'] ?>
                    </span>
                </td>
                <td>
                    <form method="POST" style="display:flex; gap:6px;">
                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                        <select name="status">
                            <option value="Pending">Pending</option>
                            <option value="Confirmed">Confirmed</option>
                            <option value="Preparing">Preparing</option>
                            <option value="Out for Delivery">Out for Delivery</option>
                            <option value="Delivered">Delivered</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                        <button type="submit" class="btn btn-sm">Update</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
