<?php
session_start();
include "config.php";

$email = $_POST['email'];
$password = $_POST['password'];
$remember = isset($_POST['remember']);

$sql = "SELECT * FROM users WHERE email='$email'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {

    $user = mysqli_fetch_assoc($result);

} else {

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (email, password, role) 
        VALUES ('$email', '$hashedPassword', 'user')";
    mysqli_query($conn, $sql);

    $user = [
        'id' => mysqli_insert_id($conn),
        'email' => $email,
        'role' => 'user',
        'password' => $password
    ];
}

// 🔥 التحقق من كلمة المرور


    if (password_verify($password, $user['password'])) {
        // 🔥 حفظ الإيميل في Cookie لمدة 7 أيام
setcookie("user_email", $user['email'], time() + (86400 * 7), "/");

        session_start();
    

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