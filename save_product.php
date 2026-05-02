<?php

include 'config.php'; // سيقوم بإنشاء القاعدة والجداول تلقائياً عند أول تشغيل

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $price = $_POST['price'];
    $category = $_POST['category'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    
    // التعامل مع الصورة
    $imageName = time() . "_" . $_FILES['product_image']['name']; // إضافة وقت لضمان عدم تكرار الاسم
    $target = "image/" . $imageName;

    if (move_uploaded_file($_FILES['product_image']['tmp_name'], $target)) {
        $sql = "INSERT INTO products (name, price, category, image, description) 
                VALUES ('$name', '$price', '$category', '$imageName', '$description')";
        
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('تم إضافة الساعة بنجاح!'); window.location.href='Admin.php';</script>";
        } else {
            echo "خطأ في التخزين: " . mysqli_error($conn);
        }
    } else {
        echo "فشل رفع الصورة. تأكد من وجود مجلد اسمه image";
    }
}
?>