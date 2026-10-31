<?php

session_start();

require_once "includes/db.php";

// Pastikan pelanggan sudah login
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

// Ambil ID pesanan dari URL
$order_id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$order_id) {
    die("Invalid order ID.");
}

// Ambil data pesanan milik pelanggan yang sedang login
$stmt = mysqli_prepare(
    $conn,
    "SELECT * FROM orders WHERE id = ? AND user_id = ?"
);

mysqli_stmt_bind_param($stmt, "ii", $order_id, $_SESSION["user_id"]);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$order = mysqli_fetch_assoc($result);

if (!$order) {
    die("Order not found.");
}

// AMBIL DETAIL PRODUK PESANAN

$item_stmt = mysqli_prepare(
    $conn,
    "SELECT * FROM order_items WHERE order_id = ?"
);

mysqli_stmt_bind_param($item_stmt, "i", $order_id);
mysqli_stmt_execute($item_stmt);

$items = mysqli_stmt_get_result($item_stmt);

?>
<!DOCTYPE html>
<html lang="id">
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
            <p>Here are the details of your sweet order!</p>
        </div>

        <div class="order-summary">

            <h2>Order #<?php echo $order["id"]; ?></h2>

            <p>
                Order Date:
                <?php echo htmlspecialchars($order["created_at"]); ?>
            </p>

            <p>
                Customer:
                <?php echo htmlspecialchars($order["customer_name"]); ?>
            </p>

            <p>
                Delivery Address:
                <?php echo htmlspecialchars($order["address"]); ?>
            </p>

            <p>
                Payment:
                <?php echo htmlspecialchars($order["payment_method"]); ?>
            </p>

            <p>
                Status:
                <?php echo htmlspecialchars($order["order_status"]); ?>
            </p>

        </div>

        <div class="order-summary">

            <h2>Products Ordered ♡</h2>

            <?php while ($item = mysqli_fetch_assoc($items)) { ?>

                <div class="cart-item">

                    <h3><?php echo htmlspecialchars($item["product_name"]); ?></h3>

                    <p>
                        Price: Rp <?php echo number_format($item["price"], 0, ',', '.'); ?>
                    </p>

                    <p>
                        Quantity: <?php echo $item["quantity"]; ?>
                    </p>

                    <p>
                        Subtotal: Rp <?php echo number_format($item["subtotal"], 0, ',', '.'); ?>
                    </p>

                </div>

            <?php } ?>

        </div>

        <div style="text-align: center; margin-top: 25px;">
            <a href="my_orders.php" class="continue-shopping">
                ♡ Back to My Orders
            </a>
        </div>

    </section>

</body>
</html>