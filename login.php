<?php
session_start();
include "config.php";

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE email='$email'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {

    $user = mysqli_fetch_assoc($result);

} else {

    // 🔥 نسجل المستخدم تلقائي
    $sql = "INSERT INTO users (email, password, role) 
            VALUES ('$email', '$password', 'user')";
    mysqli_query($conn, $sql);

    $user = [
        'id' => mysqli_insert_id($conn),
        'email' => $email,
        'role' => 'user',
        'password' => $password
    ];
}

// 🔥 التحقق من كلمة المرور


    if ($password == $user['password']) {

        session_start(); // 🔥 تأكدي إنها موجودة فوق

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['email'] = $user['email']; // ✅ مهم للواتساب

        // 🔥 لو أدمن
        if ($user['role'] == 'admin') {
            header("Location: admin.php");
            exit;
        } else {

            // ✅ جاية من checkout
            if (isset($_SESSION['from_checkout'])) {
                unset($_SESSION['from_checkout']);
                header("Location: send_to_whatsapp.php");
                exit;
            } else {
                header("Location: index.html");
                exit;
            }
        }

    } else {
        echo "كلمة المرور غلط";
    }


?>