<?php
session_start();

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id']) && !isset($_SESSION['email'])) {
    header("Location: login.html");
    exit;
}

// التوجيه لصفحة الواتساب
header("Location: send_to_whatsapp.php");
exit;
?>