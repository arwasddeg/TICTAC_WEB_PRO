<?php
include "config.php";

$id = $_GET['id'];

// حذف المنتج
$sql = "DELETE FROM products WHERE id=$id";

if (mysqli_query($conn, $sql)) {
    header("Location: admin.php"); // يرجعك للادمن بعد الحذف
    exit;
} else {
    echo "خطأ في الحذف";
}
?>