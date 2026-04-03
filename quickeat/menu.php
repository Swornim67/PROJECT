<?php
// menu.php - Show menu items for a restaurant
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$restaurant_id = $_GET['restaurant_id'];

// Get restaurant details
$r_result = mysqli_query($conn, "SELECT * FROM restaurants WHERE id=$restaurant_id");
$restaurant = mysqli_fetch_assoc($r_result);

// Get menu items for this restaurant
$m_result = mysqli_query($conn, "SELECT * FROM menu_items WHERE restaurant_id=$restaurant_id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $restaurant['name'] ?> Menu - QuickEat</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar">
    <div class="nav-logo">🍔 QuickEat</div>
    <div class="nav-links">
        <span>Hello, <?= $_SESSION['user_name'] ?>!</span>
        <a href="index.php">Home</a>
        <a href="orders.php">My Orders</a>
        <a href="logout.php">Logout</a>
    </div>
</nav>

<div class="container">
    <a href="index.php" class="back-link">← Back to Restaurants</a>
    <h2><?= $restaurant['name'] ?></h2>
    <p class="subtitle"><?= $restaurant['category'] ?> · ⏱ <?= $restaurant['delivery_time'] ?> mins</p>

    <form method="POST" action="place_order.php">
        <input type="hidden" name="restaurant_id" value="<?= $restaurant_id ?>">

        <div class="menu-list">
            <?php while ($item = mysqli_fetch_assoc($m_result)): ?>
            <div class="menu-item">
                <div class="menu-item-info">
                    <span class="menu-item-name"><?= $item['name'] ?></span>
                    <span class="menu-item-price">Rs. <?= $item['price'] ?></span>
                </div>
                <div class="menu-item-qty">
                    <label>Qty:</label>
                    <input type="number" name="qty[<?= $item['id'] ?>]" value="0" min="0" max="10"
                           data-price="<?= $item['price'] ?>"
                           data-name="<?= $item['name'] ?>"
                           class="qty-input" style="width:60px">
                </div>
            </div>
            <?php endwhile; ?>
        </div>

        <!-- Order summary -->
        <div class="order-summary">
            <h3>Order Summary</h3>
            <p>Total: <strong>Rs. <span id="total">0</span></strong></p>
            <button type="submit" class="btn" id="order-btn" disabled>Place Order</button>
        </div>
    </form>
</div>

<script>
// Calculate total dynamically
const inputs = document.querySelectorAll('.qty-input');
const totalEl = document.getElementById('total');
const orderBtn = document.getElementById('order-btn');

inputs.forEach(input => {
    input.addEventListener('input', function() {
        let total = 0;
        inputs.forEach(i => {
            total += parseInt(i.value || 0) * parseFloat(i.dataset.price);
        });
        totalEl.textContent = total;
        orderBtn.disabled = total === 0;
    });
});
</script>

</body>
</html>
