
<?php

session_start();

require_once "includes/db.php";

// Pastikan pelanggan sudah login
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

// Pastikan data dikirim melalui POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: checkout.php");
    exit;
}

// Ambil data pelanggan
$user_id = $_SESSION["user_id"];
$customer_name = trim($_POST["name"] ?? "");
$phone = trim($_POST["phone"] ?? "");
$address = trim($_POST["address"] ?? "");
$payment_method = $_POST["payment"] ?? "";

// Validasi data pelanggan
if (
    $customer_name === "" ||
    $phone === "" ||
    $address === "" ||
    !in_array($payment_method, ["Transfer Bank", "COD"], true)
) {
    die("Please complete all checkout information.");
}

// BACA DATA KERANJANG
$cart_data = $_POST["cart_data"] ?? "";
$cart = json_decode($cart_data, true);

if (!is_array($cart) || empty($cart)) {
    die("Your cart is empty or invalid.");
}

// HITUNG TOTAL DARI DATABASE
$total = 0;
$valid_items = [];

foreach ($cart as $item) {

    $product_name = $item["name"] ?? "";

    $quantity = filter_var(
        $item["quantity"] ?? null,
        FILTER_VALIDATE_INT
    );

    if ($product_name === "" || $quantity === false || $quantity < 1) {
        die("Invalid cart item.");
    }

    $stmt = mysqli_prepare(
        $conn,
        "SELECT name, price FROM products WHERE name = ?"
    );

    if (!$stmt) {
        die("Failed to prepare product query.");
    }

    mysqli_stmt_bind_param($stmt, "s", $product_name);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $product = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    if (!$product) {
        die("Product not found: " . htmlspecialchars($product_name));
    }

    $price = (float) $product["price"];
    $subtotal = $price * $quantity;

    $total += $subtotal;

    $valid_items[] = [
        "name" => $product["name"],
        "price" => $price,
        "quantity" => $quantity,
        "subtotal" => $subtotal
    ];
}

// MULAI TRANSAKSI DATABASE
mysqli_begin_transaction($conn);

// SIMPAN PESANAN UTAMA
$stmt = mysqli_prepare(
    $conn,
    "INSERT INTO orders
    (user_id, customer_name, phone, address, payment_method, total)
    VALUES (?, ?, ?, ?, ?, ?)"
);

if (!$stmt) {
    mysqli_rollback($conn);
    die("Failed to prepare order query.");
}

mysqli_stmt_bind_param(
    $stmt,
    "issssd",
    $user_id,
    $customer_name,
    $phone,
    $address,
    $payment_method,
    $total
);

if (!mysqli_stmt_execute($stmt)) {
    mysqli_rollback($conn);
    die("Failed to save order: " . mysqli_stmt_error($stmt));
}

// Ambil ID pesanan yang baru dibuat
$order_id = mysqli_insert_id($conn);

mysqli_stmt_close($stmt);

// SIMPAN DETAIL PRODUK PESANAN
$stmt = mysqli_prepare(
    $conn,
    "INSERT INTO order_items
    (order_id, product_name, price, quantity, subtotal)
    VALUES (?, ?, ?, ?, ?)"
);

if (!$stmt) {
    mysqli_rollback($conn);
    die("Failed to prepare order items query.");
}

foreach ($valid_items as $item) {

    mysqli_stmt_bind_param(
        $stmt,
        "isdid",
        $order_id,
        $item["name"],
        $item["price"],
        $item["quantity"],
        $item["subtotal"]
    );

    if (!mysqli_stmt_execute($stmt)) {
        mysqli_rollback($conn);
        die("Failed to save order items: " . mysqli_stmt_error($stmt));
    }
}

mysqli_stmt_close($stmt);

// SIMPAN SEMUA PERUBAHAN
if (!mysqli_commit($conn)) {
    mysqli_rollback($conn);
    die("Failed to complete order.");
}

// PESANAN BERHASIL
header("Location: success.php");
exit;

?>