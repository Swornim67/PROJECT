<?php
// place_order.php - Handles order submission
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id       = $_SESSION['user_id'];
    $restaurant_id = $_POST['restaurant_id'];
    $quantities    = $_POST['qty'];   // array: [item_id => qty]

    $ordered_items = [];
    $total = 0;

    // Loop through each item quantity
    foreach ($quantities as $item_id => $qty) {
        $qty = (int)$qty;
        if ($qty > 0) {
            // Get item price and name
            $item_result = mysqli_query($conn, "SELECT * FROM menu_items WHERE id=$item_id");
            $item = mysqli_fetch_assoc($item_result);

            $subtotal = $item['price'] * $qty;
            $total += $subtotal;
            $ordered_items[] = $qty . "x " . $item['name'];
        }
    }

    if (empty($ordered_items)) {
        header("Location: menu.php?restaurant_id=$restaurant_id");
        exit;
    }

    $item_names = implode(", ", $ordered_items);

    // Insert order into DB
    $sql = "INSERT INTO orders (user_id, restaurant_id, item_name, quantity, total, status)
            VALUES ($user_id, $restaurant_id, '$item_names', 1, $total, 'Pending')";

    if (mysqli_query($conn, $sql)) {
        header("Location: orders.php?success=1");
    } else {
        echo "Error placing order: " . mysqli_error($conn);
    }
}
?>
