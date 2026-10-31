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
    <title>Checkout - Bakery Bliss</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <section class="cart-page">
        <div style="text-align: center; margin-bottom: 25px;">
            <a href="index.php" class="continue-shopping">
                ♡ Back to Menu
            </a>
        </div>

        <div class="cart-title">
            <h1>Checkout ♡</h1>
            <p>Almost there! Complete your order.</p>
        </div>

        <div class="checkout-container">
            <div class="order-summary">
                <h2>Order Summary ♡</h2>
                <div id="checkout-summary">
                    Loading your order...
                </div>
            </div>

            <form id="checkout-form" action="process_order.php" method="POST">
                <input type="hidden" name="cart_data" id="cart-data">
                <h2>Customer Information</h2>

                <label>Full Name</label>
                <input type="text" name="name"
                    placeholder="Enter your full name" required>

                <label>WhatsApp Number</label>
                <input type="tel" name="phone"
                    placeholder="08xxxxxxxxxx" required>

                <label>Delivery Address</label>
                <textarea name="address"
                    placeholder="Enter your complete address"
                    required></textarea>

                <h2>Payment Method</h2>

                <select name="payment" required>
                    <option value="">Choose payment method</option>
                    <option value="Transfer Bank">Bank Transfer</option>
                    <option value="COD">Cash on Delivery (COD)</option>
                </select>

                <button type="submit" class="checkout-button">
                    Place Order ♡
                </button>

            </form>

        </div>

    </section>
<script src="js/cart.js"></script>
</body>
</html>