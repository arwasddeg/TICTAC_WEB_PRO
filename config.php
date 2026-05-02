<?php
$host = "localhost";
$user = "root";
$pass = "";

// 1. الاتصال بـ XAMPP (السيرفر)
$conn = mysqli_connect($host, $user, $pass);

if (!$conn) {
    die("فشل الاتصال بالسيرفر: " . mysqli_connect_error());
}

// 2. إنشاء قاعدة البيانات إذا لم تكن موجودة
$db_name = "tictac_db";
$sql_db = "CREATE DATABASE IF NOT EXISTS $db_name CHARACTER SET utf8 COLLATE utf8_general_ci";
mysqli_query($conn, $sql_db);

// 3. اختيار قاعدة البيانات للعمل عليها
mysqli_select_db($conn, $db_name);

// 4. كود إنشاء جدول الساعات
$table_products = "CREATE TABLE IF NOT EXISTS products (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    category VARCHAR(50) NOT NULL,
    image VARCHAR(255) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
mysqli_query($conn, $table_products);

// 5. كود إنشاء جدول المستخدمين
$table_users = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    password VARCHAR(255),
    role ENUM('admin','user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
mysqli_query($conn, $table_users);

// 6. كود إنشاء جدول الطلبات
$table_orders = "CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11),
    total DECIMAL(10,2),
    status VARCHAR(50) DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
)";
mysqli_query($conn, $table_orders);

// 7. جدول تفاصيل الطلبات (جديد)
$table_order_items = "CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    product_price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
)";
mysqli_query($conn, $table_order_items);

// 8. إدخال أدمن افتراضي
$check_admin = mysqli_query($conn, "SELECT * FROM users WHERE email='admin@gmail.com'");

if (mysqli_num_rows($check_admin) == 0) {
    $insert_admin = "INSERT INTO users (name, email, password, role)
    VALUES ('Admin', 'admin@gmail.com', '123456', 'admin')";
    mysqli_query($conn, $insert_admin);
}

// 9. جدول السلة (اختياري)
$table_cart = "CREATE TABLE IF NOT EXISTS cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    product_id INT,
    quantity INT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";
mysqli_query($conn, $table_cart);
?>