<?php

require_once "includes/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = mysqli_prepare($conn, "INSERT INTO users (name, email, password) VALUES (?, ?, ?)");

    mysqli_stmt_bind_param($stmt, "sss", $name, $email, $hashedPassword);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>
            alert('Account created successfully! ♡');
            window.location.href = 'login.php';
        </script>";
        exit;
    } else {
        echo "<script>
            alert('Registration failed. Email may already be registered.');
        </script>";
    }

    mysqli_stmt_close($stmt);
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Bakery Bliss</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <section class="cart-page">

        <div class="cart-title">
            <h1>Create Your Account ♡</h1>
            <p>Join Bakery Bliss and enjoy your sweetest moments.</p>
        </div>

        <div class="checkout-container">

            <form action="signup.php" method="POST">

                <h2>Sign Up</h2>

                <label>Full Name</label>
                <input type="text" name="name"
                    placeholder="Enter your full name" required>

                <label>Email Address</label>
                <input type="email" name="email"
                    placeholder="Enter your email" required>

                <label>Password</label>
                <input type="password" name="password"
                    placeholder="Create a password" required>

                <button type="submit" class="checkout-button">
                    Create Account ♡
                </button>

                <p style="text-align: center; margin-top: 20px;">
                    Already have an account?
                    <a href="login.php">Login here</a>
                </p>

            </form>

        </div>

        <div style="text-align: center; margin-top: 25px;">
            <a href="index.php" class="continue-shopping">
                ♡ Back to Menu
            </a>
        </div>

    </section>

</body>
</html>