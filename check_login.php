<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    $_SESSION['from_checkout'] = true;
    header("Location: login.html");
    exit;
}

// لو مسجل
header("Location: send_to_whatsapp.php");
exit;