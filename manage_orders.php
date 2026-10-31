<?php

session_start();

if (!isset($_SESSION["user_id"]) || ($_SESSION["user_role"] ?? "") !== "admin") {
    header("Location: login.php");
    exit;
}

require_once "includes/db.php";
if (isset($_POST["update_status"])) {

    $order_id = $_POST["order_id"];
    $new_status = $_POST["order_status"];

    $stmt = mysqli_prepare($conn, "UPDATE orders SET order_status = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "si", $new_status, $order_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("Location: manage_orders.php");
    exit;
}
$order_query = mysqli_query($conn, "
    SELECT orders.*, users.email
    FROM orders
    JOIN users ON orders.user_id = users.id
    ORDER BY orders.created_at DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders - Bakery Bliss</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <section class="cart-page">

        <div class="cart-title">
            <h1>Manage Orders ♡</h1>
            <p>View and manage customer orders</p>
            <a href="admin_dashboard.php" class="continue-shopping">
                ♡ Back to Dashboard
            </a>
            <div class="cart-container">

                <?php while ($order = mysqli_fetch_assoc($order_query)) { ?>

                    <div class="cart-item">

                        <h3>Order #<?php echo $order["id"]; ?></h3>

                        <p>Customer: <?php echo htmlspecialchars($order["customer_name"]); ?></p>

                        <p>Email: <?php echo htmlspecialchars($order["email"]); ?></p>

                        <p>Phone: <?php echo htmlspecialchars($order["phone"]); ?></p>

                        <p>Total: Rp<?php echo number_format($order["total"], 0, ',', '.'); ?></p>

                        <p>Status: <?php echo htmlspecialchars($order["order_status"]); ?></p>
                        <form method="POST" action="manage_orders.php">

                            <input type="hidden" name="order_id"
                                value="<?php echo $order["id"]; ?>">

                            <label>Update Order Status</label>

                            <select name="order_status">
                                <option value="Pending">Pending</option>
                                <option value="Processing">Processing</option>
                                <option value="Completed">Completed</option>
                                <option value="Cancelled">Cancelled</option>
                            </select>

                            <button type="submit" name="update_status">
                                Update Status
                            </button>

                        </form>
                        <a href="admin_order_details.php?id=<?php echo $order["id"]; ?>"
                           class="continue-shopping">
                            View Order Details ♡
                        </a>
                    </div>

                <?php } ?>

            </div>
        </div>

    </section>

</body>
</html>