<?php
session_start();
include "config.php";

$data = json_decode(file_get_contents("php://input"), true);

$user_id = $_SESSION['user_id'];

foreach ($data as $item) {
    $product_id = $item['id'];
    $qty = $item['qty'];

    mysqli_query($conn, "INSERT INTO cart (user_id, product_id, quantity)
    VALUES ($user_id, $product_id, $qty)");
}
?>