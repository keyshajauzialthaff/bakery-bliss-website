<?php

session_start();
require_once "includes/db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $stmt = mysqli_prepare($conn, "SELECT id, name, password, role FROM users WHERE email = ?");

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user["password"])) {

        session_regenerate_id(true);

        $_SESSION["user_id"] = $user["id"];
        $_SESSION["user_name"] = $user["name"];
        $_SESSION["user_role"] = $user["role"];

        if ($user["role"] == "admin") {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: index.php");
        }
        exit;
    } else {
        $error = "Email or password is incorrect.";
    }

    mysqli_stmt_close($stmt);
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Bakery Bliss</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <section class="cart-page">

        <div class="cart-title">
            <h1>Welcome Back ♡</h1>
            <p>We're happy to see you again!</p>
        </div>

        <div class="checkout-container">

            <form action="login.php" method="POST">

                <h2>Login to Your Account</h2>
                <?php if (!empty($error)) { ?>
                    <p style="color: #d95378; text-align: center;">
                        <?php echo htmlspecialchars($error); ?>
                    </p>
                <?php } ?>

                <label>Email Address</label>
                <input type="email" name="email"
                    placeholder="Enter your email" required>

                <label>Password</label>
                <input type="password" name="password"
                    placeholder="Enter your password" required>

                <button type="submit" class="checkout-button">
                    Login ♡
                </button>

                <p style="text-align: center; margin-top: 20px;">
                    Don't have an account?
                    <a href="signup.php">Sign Up here</a>
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