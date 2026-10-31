<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Bakery Bliss</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <section class="cart-page">

        <div class="cart-title">
            <h1>My Shopping Cart ♡</h1>
            <p>Your sweetest treats are waiting for you.</p>
        </div>

        <div id="cart-container">
            Your cart is empty.
        </div>

        <div style="text-align: center;">
            <a href="index.php" class="continue-shopping">♡ Back to Menu</a>
            <br><br>
            <a href="checkout.php"
                class="checkout-button"
                id="checkout-link"
                style="display: inline-block; text-decoration: none;">
                Proceed to Checkout ♡
            </a>
        </div>
    </section>

    <script src="js/cart.js"></script>

</body>
</html>