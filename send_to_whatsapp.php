<?php
session_start();

$whatsapp_number = "0912958230";

$email = $_SESSION['email'] ?? "بدون إيميل";
$cart = $_SESSION['cart'] ?? [];

$message = "🛒 طلب جديد\n\n";
$message .= "📧 الإيميل: $email\n\n";
$message .= "📦 السلة:\n";

$total = 0;

foreach ($cart as $item) {

    $name = $item['name'];
    $qty = $item['quantity'];
    $price = $item['price'];

    $subtotal = $qty * $price;
    $total += $subtotal;

    $message .= "• $name\n";
    $message .= "  الكمية: $qty\n";
    $message .= "  السعر: €$price\n";
    $message .= "  ---------\n";
}

$message .= "\n💰 الإجمالي: €$total";

$encoded = urlencode($message);

$url = "https://wa.me/$whatsapp_number?text=$encoded";

header("Location: $url");
exit;
?>