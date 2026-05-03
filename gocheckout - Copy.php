<?php
session_start();

// 🔥 ديما نقولو إنه جاية من checkout
$_SESSION['from_checkout'] = true;

// لو مش مسجلة دخول
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// لو مسجلة دخول → واتساب مباشرة
header("Location: send_to_whatsapp.php");
exit;
?>