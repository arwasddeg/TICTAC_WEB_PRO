<?php
session_start();
include "config.php";

$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = mysqli_real_escape_string($conn, $_POST['password']);

$sql = "SELECT * FROM users WHERE email='$email'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);

    if ($password == $user['password']) {

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];

        // 👇 هنا السحر
        if (isset($_SESSION['from_checkout'])) {
            unset($_SESSION['from_checkout']);
            header("Location: send_to_whatsapp.php");
        } else {
            header("Location: index.html");
        }

        exit;
    } else {
        echo "<script>alert('كلمة المرور خطأ'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('الحساب غير موجود'); window.history.back();</script>";
}
?>