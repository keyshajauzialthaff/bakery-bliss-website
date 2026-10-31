<?php

session_start();

require_once "includes/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION["user_id"];

$query = mysqli_prepare(
    $conn,
    "SELECT * FROM orders
     WHERE user_id = ?
     ORDER BY created_at DESC"
);

if (!$query) {
    die("Query preparation failed: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($query, "i", $user_id);

if (!mysqli_stmt_execute($query)) {
    die("Query execution failed: " . mysqli_stmt_error($query));
}

$orders = mysqli_stmt_get_result($query);

if (!$orders) {
    die("Failed to retrieve orders: " . mysqli_error($conn));
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - Bakery Bliss</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <section class="cart-page">

        <div class="cart-title">
            <h1>My Orders ♡</h1>
            <p>Here are your sweet orders!</p>
        </div>

        <div class="cart-container">

            <?php if (mysqli_num_rows($orders) > 0) { ?>

                <?php while ($order = mysqli_fetch_assoc($orders)) { ?>

                    <div class="cart-item">

                        <h3>Order #<?php echo $order["id"]; ?></h3>

                        <p>
                            Date:
                            <?php echo htmlspecialchars($order["created_at"]); ?>
                        </p>

                        <p>
                            Total:
                            Rp <?php echo number_format($order["total"], 0, ',', '.'); ?>
                        </p>

                        <p>
                            Payment:
                            <?php echo htmlspecialchars($order["payment_method"]); ?>
                        </p>

                        <p class="order-status">
                            Status:
                            <span class="status-<?php echo strtolower($order["order_status"]); ?>">
                                <?php echo htmlspecialchars($order["order_status"]); ?>
                            </span>
                        </p>
                        <a href="order_details.php?id=<?php echo (int) $order["id"]; ?>" class="continue-shopping">
                            View Details ♡
                        </a>

                    </div>

                <?php } ?>

            <?php } else { ?>

                <p>You haven't placed any orders yet ♡</p>

            <?php } ?>

        </div>

        <div style="text-align: center; margin-top: 25px;">
            <a href="index.php" class="continue-shopping">
                ♡ Back to Menu
            </a>
        </div>

    </section>

</body>
</html>