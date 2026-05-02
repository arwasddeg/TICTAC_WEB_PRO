<?php
session_start();
include "config.php";

/* 1. التأكد من تسجيل الدخول */
if (!isset($_SESSION['user_id'])) {
    die("User not logged in");
}

$user_id = $_SESSION['user_id'];

/* 2. استقبال بيانات السلة */
$data = json_decode(file_get_contents("php://input"), true);

/* 3. التأكد أن البيانات صحيحة */
if (!is_array($data)) {
    die("No cart data received or invalid JSON");
}

/* 4. حفظ كل منتج في السلة */
foreach ($data as $item) {

    // التأكد من وجود القيم
    if (!isset($item['id']) || !isset($item['qty'])) {
        continue;
    }

    $product_id = (int)$item['id'];
    $qty = (int)$item['qty'];

    mysqli_query($conn, "
        INSERT INTO cart (user_id, product_id, quantity)
        VALUES ($user_id, $product_id, $qty)
    ");
}

echo "Cart saved successfully";
?>