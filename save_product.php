<?php
include 'config.php'; 

// التأكد أن البيانات جاءت عبر طريقة POST (من الفورمة)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // تنظيف النصوص المدخلة قبل وضعها في الاستعلام[cite: 14]
    $name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $price = $_POST['price'];
    $category = $_POST['category'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    
    // تجهيز اسم فريد للصورة لعدم تداخل الملفات
    $imageName = time() . "_" . $_FILES['product_image']['name']; 
    $target = "image/" . $imageName; // المسار النهائي للملف

    // محاولة نقل الملف من السيرفر المؤقت إلى مجلد الصور[cite: 13]
    if (move_uploaded_file($_FILES['product_image']['tmp_name'], $target)) {
        
        // جملة الإدخال في جدول المنتجات[cite: 14]
        $sql = "INSERT INTO products (name, price, category, image, description) 
                VALUES ('$name', '$price', '$category', '$imageName', '$description')";
        
        // تنفيذ الاستعلام والتأكد من نجاحه
        if (mysqli_query($conn, $sql)) {
            // تنبيه المستخدم وتوجيهه لصفحة الأدمن[cite: 13]
            echo "<script>alert('تم إضافة الساعة بنجاح!'); window.location.href='Admin.php';</script>";
        } else {
            echo "خطأ في التخزين: " . mysqli_error($conn);
        }
    } else {
        // رسالة خطأ في حال فشل الرفع (غالباً بسبب صلاحيات المجلد)
        echo "فشل رفع الصورة. تأكد من وجود مجلد اسمه image";
    }
}
?>