<?php
session_start();
include 'config.php';

// التأكد من أن المستخدم سجل دخوله
if (!isset($_SESSION['user_id']) && !isset($_SESSION['email'])) {
    echo json_encode(['success' => false, 'message' => 'يجب تسجيل الدخول أولاً']);
    exit;
}

// استقبال البيانات
$input = json_decode(file_get_contents('php://input'), true);

// إذا كانت البيانات قادمة من FormData (POST عادي)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['name'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $total = floatval($_POST['total']);
    $user_id = $_SESSION['user_id'] ?? 0;
    
    $sql = "INSERT INTO orders (user_id, total, status, created_at) 
            VALUES ('$user_id', '$total', 'pending', NOW())";
    
    if(mysqli_query($conn, $sql)){
        $order_id = mysqli_insert_id($conn);
        
        // جلب السلة من localStorage (سيتم إرسالها مع الطلب)
        // أو يمكننا استقبالها كـ JSON
        echo json_encode(['success' => true, 'order_id' => $order_id, 'message' => 'تم حفظ الطلب بنجاح']);
    } else {
        echo json_encode(['success' => false, 'message' => 'خطأ في حفظ الطلب: ' . mysqli_error($conn)]);
    }
}
// إذا كانت البيانات قادمة كـ JSON من send_to_whatsapp.php
else if ($input && isset($input['items'])) {
    $user_id = $input['user_id'] ?? $_SESSION['user_id'] ?? 0;
    $total = $input['total'] ?? 0;
    $items = $input['items'];
    
    // إنشاء جدول تفاصيل الطلب إذا لم يكن موجوداً
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
    
    // حفظ الطلب الرئيسي
    $sql_order = "INSERT INTO orders (user_id, total, status, created_at) 
                  VALUES ('$user_id', '$total', 'pending', NOW())";
    
    if (mysqli_query($conn, $sql_order)) {
        $order_id = mysqli_insert_id($conn);
        
        // حفظ تفاصيل الطلب
        foreach ($items as $item) {
            $product_name = mysqli_real_escape_string($conn, $item['name']);
            $product_price = floatval($item['price']);
            $quantity = intval($item['quantity']);
            
            $sql_item = "INSERT INTO order_items (order_id, product_name, product_price, quantity) 
                         VALUES ('$order_id', '$product_name', '$product_price', '$quantity')";
            mysqli_query($conn, $sql_item);
        }
        
        echo json_encode(['success' => true, 'order_id' => $order_id]);
    } else {
        echo json_encode(['success' => false, 'message' => mysqli_error($conn)]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'لم يتم استلام بيانات صحيحة']);
}
?>