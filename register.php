<?php
include "config.php";

$email = $_POST['email'];
$password = $_POST['password'];

// نضيف المستخدم
$sql = "INSERT INTO users (email, password, role) 
        VALUES ('$email', '$password', 'user')";

if (mysqli_query($conn, $sql)) {
    echo "تم التسجيل بنجاح";
} else {
    echo "خطأ: " . mysqli_error($conn);
}
?>