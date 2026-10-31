<?php

session_start();

if (!isset($_SESSION["user_id"]) || ($_SESSION["user_role"] ?? "") !== "admin") {
    header("Location: login.php");
    exit;
}

require_once "includes/db.php";

$order_id = $_GET["id"] ?? 0;

$stmt = mysqli_prepare($conn, "SELECT * FROM orders WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $order_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$order = mysqli_fetch_assoc($result);

if (!$order) {
    die("Order not found.");
}
$item_stmt = mysqli_prepare($conn, "SELECT * FROM order_items WHERE order_id = ?");
mysqli_stmt_bind_param($item_stmt, "i", $order_id);
mysqli_stmt_execute($item_stmt);

$items_result = mysqli_stmt_get_result($item_stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - Bakery Bliss</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<section class="cart-page">

    <div class="cart-title">
        <h1>Order Details ♡</h1>
        <p>Bakery Bliss Customer Order</p>
        <a href="admin_dashboard.php" class="continue-shopping">
            ♡ Back to Dashboard
        </a>    
    </div>

    <div class="order-summary">
        <h2>Order #<?php echo $order["id"]; ?></h2>

        <p><strong>Customer:</strong>
            <?php echo htmlspecialchars($order["customer_name"]); ?></p>

        <p><strong>Phone:</strong>
            <?php echo htmlspecialchars($order["phone"]); ?></p>

        <p><strong>Address:</strong>
            <?php echo htmlspecialchars($order["address"]); ?></p>

        <p><strong>Payment:</strong>
            <?php echo htmlspecialchars($order["payment_method"]); ?></p>

        <p><strong>Status:</strong>
            <?php echo htmlspecialchars($order["order_status"]); ?></p>

        <p><strong>Total:</strong>
            Rp<?php echo number_format($order["total"], 0, ',', '.'); ?></p>
    </div>

    <div class="cart-container">

        <h2>Ordered Products</h2>

        <?php while ($item = mysqli_fetch_assoc($items_result)) { ?>

            <div class="cart-item">

                <h3><?php echo htmlspecialchars($item["product_name"]); ?></h3>

                <p>Price: Rp<?php echo number_format($item["price"], 0, ',', '.'); ?></p>

                <p>Quantity: <?php echo $item["quantity"]; ?></p>

                <p>Subtotal: Rp<?php echo number_format($item["subtotal"], 0, ',', '.'); ?></p>

            </div>

        <?php } ?>

    </div>

    <div style="text-align: center; margin-top: 25px;">
        <a href="manage_orders.php" class="continue-shopping">
            ♡ Back to Manage Orders
        </a>
    </div>

</section>

</body>
</html>