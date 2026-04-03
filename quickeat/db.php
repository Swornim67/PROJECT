<?php
// db.php - Database connection

$host = "localhost";
$user = "root";
$pass = "";        // change this if your MySQL has a password
$db   = "quickeat";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
