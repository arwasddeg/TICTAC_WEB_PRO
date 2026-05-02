<?php
session_start();
include "conn.php";

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE email='$email'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {

    $user = mysqli_fetch_assoc($result);

    if ($password == $user['password']) {

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        // 🔥 لو أدمن
        if ($user['role'] == 'admin') {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: index.html");
        }

    } else {
        echo "كلمة المرور غلط";
    }

} else {
    echo "المستخدم غير موجود";
}
?>