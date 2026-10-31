<?php

session_start();

if (!isset($_SESSION["user_id"]) || ($_SESSION["user_role"] ?? "") !== "admin") {
    header("Location: login.php");
    exit;
}

require_once "includes/db.php";
$order_query = mysqli_query($conn, "SELECT COUNT(*) AS total_orders FROM orders");
$order_data = mysqli_fetch_assoc($order_query);
$total_orders = $order_data["total_orders"];
$pending_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders WHERE order_status = 'Pending'");
$pending_orders = mysqli_fetch_assoc($pending_query)["total"];

$processing_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders WHERE order_status = 'Processing'");
$processing_orders = mysqli_fetch_assoc($processing_query)["total"];

$completed_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders WHERE order_status = 'Completed'");
$completed_orders = mysqli_fetch_assoc($completed_query)["total"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Bakery Bliss</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<section class="cart-page">

    <div class="cart-title">
        <h1>Admin Dashboard ♡</h1>
        <p>Welcome to Bakery Bliss Administration</p>
    </div>

    <div class="admin-stats">

        <div class="admin-card">
            <h3>Total Orders</h3>
            <h2><?php echo $total_orders; ?></h2>
        </div>

        <div class="admin-card">
            <h3>Pending Orders</h3>
            <h2><?php echo $pending_orders; ?></h2>
        </div>

        <div class="admin-card">
            <h3>Processing Orders</h3>
            <h2><?php echo $processing_orders; ?></h2>
        </div>

        <div class="admin-card">
            <h3>Completed Orders</h3>
            <h2><?php echo $completed_orders; ?></h2>
        </div>

    </div>

    <div class="admin-menu">
        <a href="index.php">View Website</a>
        <a href="manage_orders.php">Manage Orders</a>
        <a href="logout.php">Logout</a>
    </div>

</section>

</body>
</html>