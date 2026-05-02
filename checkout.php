<?php
if (isset($_POST['checkout'])) {

    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $product = $_POST['product'];

    // رقمك في واتساب
    $whatsapp_number = "218919233764";

    // الرسالة
    $message = "طلب جديد:\n";
    $message .= "الاسم: $name\n";
    $message .= "الهاتف: $phone\n";
    $message .= "المنتج: $product";

    // تشفير الرابط
    $encoded_message = urlencode($message);

    // رابط واتساب
    $url = "https://wa.me/$whatsapp_number?text=$encoded_message";

    // تحويل للواتساب
    header("Location: $url");
    exit;
}
?>