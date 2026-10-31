<?php
session_start();

require_once "includes/db.php";

$query = mysqli_query($conn, "SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bakery Bliss</title>
    <link rel="stylesheet" href="css/style.css">
</head>


<body>
<header class="header">
    <!-- LOGO -->
    <div class="logo">
        Bakery<span>Bliss.</span>
    </div>

    <!-- NAVIGATION -->
    <nav class="navbar">

        <?php if (isset($_SESSION["user_id"]) && ($_SESSION["user_role"] ?? "") == "admin") { ?>

            <a href="index.php">View Website</a>
            <a href="admin_dashboard.php">Admin Dashboard</a>
            <a href="manage_orders.php">Manage Orders</a>

        <?php } else { ?>

            <a href="index.php" class="active">Home</a>
            <a href="#menu">Menu</a>
            <a href="#about">About Us</a>
            <a href="#contact">Contact</a>
            <a href="cart.php">Cart 🛒</a>

        <?php } ?>

    </nav>

    <!-- LOGIN BUTTON -->
    <div class="nav-buttons">

        <?php if (isset($_SESSION["user_id"])) { ?>

            <span>
                Hi, <?php echo htmlspecialchars($_SESSION["user_name"]); ?> ♡
            </span>

            <?php if (($_SESSION["user_role"] ?? "") == "customer") { ?>

                <a href="my_orders.php" class="login-btn">My Orders</a>

            <?php } ?>

            <a href="logout.php" class="login-btn">Logout</a>

        <?php } else { ?>

            <a href="login.php" class="login-btn">Login</a>
            <a href="signup.php" class="signup-btn">Sign Up</a>

        <?php } ?>

    </div>
</header>
    <section class="hero">
    <div class="hero-content">

        <p class="hero-label">♡ FRESHLY BAKED WITH LOVE</p>

        <h1>
            Happiness is<br>
            <span>Homemade.</span>
        </h1>

        <p class="hero-description">
            Discover the sweetest moments with our freshly
            baked cakes and pastries, made with love just for you.
        </p>

        <a href="#menu" class="button">
            Explore Our Menu →
        </a>

    </div>

    <div class="hero-image">
        <img src="assets/cake.png" alt="Delicious Bakery Cake">
    </div>
</section>
<section class="products" id="menu">

    <div class="section-title">
        <p>♡ OUR FAVORITES</p>
        <h2>Our Best Sellers</h2>
        <span>Made with love, baked with happiness.</span>
    </div>

    <div class="product-container">
        <?php while ($product = mysqli_fetch_assoc($query)) { ?>
            <div class="product-card">
                <img src="assets/<?php echo htmlspecialchars($product['image']); ?>"
                    alt="<?php echo htmlspecialchars($product['name']); ?>">
                <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                <p><?php echo htmlspecialchars($product['description']); ?></p>
                <h4>Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></h4>
                <button class="add-to-cart"
                    data-name="<?php echo htmlspecialchars($product['name'], ENT_QUOTES); ?>"
                    data-price="<?php echo $product['price']; ?>">
                    Add to Cart
                </button>
            </div>
        <?php } ?>
    </div>
</section>

<!-- ABOUT US -->
<section class="about-section" id="about">

    <div class="about-content">
        <p class="about-subtitle">♡ OUR STORY</p>

        <h2>A Little Sweetness in Every Bite</h2>

        <p>
            Welcome to Bakery Bliss, where every treat is made
            with love and happiness. We believe every special
            moment deserves something sweet.
        </p>

        <p>
            From delightful cakes to delicious cupcakes,
            every creation is prepared with care and quality
            ingredients to bring joy to your day.
        </p>

        <h3>Happiness is Homemade ♡</h3>
    </div>

</section>


<!-- CONTACT US -->
<section class="contact-section" id="contact">

    <div class="section-title">
        <p>♡ GET IN TOUCH</p>
        <h2>Contact Us</h2>
        <span>We would love to hear from you!</span>
    </div>

    <div class="contact-content">

        <div class="contact-card">
            <h3>♡ WhatsApp</h3>
            <p>Have questions or want to order?</p>
            <a href="https://wa.me/6285659521324" target="_blank">
                Chat With Us
            </a>
        </div>

        <div class="contact-card">
            <h3>♡ Instagram</h3>
            <p>Follow us for more sweet updates!</p>
            <a href="https://instagram.com/keyshaajn" target="_blank">
                Visit Instagram
            </a>
        </div>

    </div>

</section>
    <footer class="footer">
        © 2026 Bakery Bliss. All Rights Reserved.
    </footer>
<script src="js/cart.js"></script>
</body>
</html>