<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Successful - Bakery Bliss</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <section class="cart-page">
        <div class="cart-title">
            <h1>Thank You for Your Order! ♡</h1>
            <p>Your order has been received successfully.</p>
            <p>We can't wait to make something sweet for you!</p>

            <a href="index.php" class="continue-shopping">
                ♡ Back to Menu
            </a>
        </div>
    </section>

    <script>
        localStorage.removeItem("bakeryCart");
    </script>

</body>
</html>