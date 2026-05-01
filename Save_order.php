<?php
include 'config.php';

$name = $_POST['name'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$total = $_POST['total'];

$sql = "INSERT INTO orders (customer_name, phone, address, total)
        VALUES ('$name', '$phone', '$address', '$total')";

if(mysqli_query($conn, $sql)){
    echo "تم الطلب بنجاح";
} else {
    echo "خطأ";
}
?>