<?php include 'config.php'; ?>
<!DOCTYPE html>

<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم | TICTAC Admin</title>
    <style>
        /* التنسيق المستوحى من ملفاتك (MprPage.css و Sp.css) */
        :root {
            --primary-bg: linear-gradient(to right bottom, #2b6081, #14425f, #030f16, #03111e, #031f30, #3065a5, #2e4968);
            --card-bg: rgba(255, 255, 255, 0.05);
            --accent-color: #00d4ff; /* الأزرق الخاص بـ TICTAC */
            --text-color: #e5e7eb;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: "Times New Roman", serif;
            background: var(--primary-bg);
            background-attachment: fixed;
            color: var(--text-color);
            min-height: 100vh;
        }

        .admin-container {
            padding: 40px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .logo {
            font-size: 28px;
            font-weight: bold;
            letter-spacing: 2px;
        }

        .logo span { color: var(--accent-color); }

        /* كروت الإحصائيات */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: var(--card-bg);
            padding: 25px;
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
            backdrop-filter: blur(10px);
            transition: 0.3s;
        }

        .stat-card:hover { transform: translateY(-5px); background: rgba(255,255,255,0.1); }
        .stat-card h3 { font-size: 14px; color: #aaa; margin-bottom: 10px; text-transform: uppercase; }
        .stat-card p { font-size: 28px; font-weight: bold; color: var(--accent-color); margin: 0; }

        /* قسم إدارة المنتجات */
        .content-section {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 30px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .btn-add {
            background: var(--accent-color);
            color: #030f16;
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
        }

        .btn-add:hover { box-shadow: 0 0 20px rgba(0, 212, 255, 0.5); }

        /* الجدول */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th {
            text-align: right;
            padding: 15px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.1);
            color: var(--accent-color);
            font-size: 18px;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            vertical-align: middle;
        }

        .product-img {
            width: 60px;
            height: 60px;
            object-fit: contain;
            background: rgba(255,255,255,0.03);
            border-radius: 8px;
            padding: 5px;
        }

        /* أزرار التحكم */
        .actions button {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
            padding: 8px 15px;
            border-radius: 6px;
            cursor: pointer;
            margin-left: 5px;
            transition: 0.3s;
        }

        .btn-edit:hover { background: #ffc107; color: #000; border-color: #ffc107; }
        .btn-delete:hover { background: #ff4d4d; border-color: #ff4d4d; }

    </style>
</head>
<body>

<div class="admin-container">
    <div class="header">
        <div class="logo">TIC<span>TAC</span> - Admin</div>
        <div class="admin-info">مرحباً بك في لوحة الإدارة</div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <h3>إجمالي الساعات</h3>
            <p>24</p>
        </div>
        <div class="stat-card">
            <h3>الطلبات الجديدة</h3>
            <p>5</p>
        </div>
        <div class="stat-card">
            <h3>المبيعات (الشهر)</h3>
            <p>€4,500</p>
        </div>
        <div class="stat-card">
            <h3>المستخدمين</h3>
            <p>150</p>
        </div>
    </div>

    <div class="content-section">
        <div class="section-header">
            <h2>إدارة المخزون</h2>
            <a href="AddNewWatch.php" class="btn-add">+ إضافة ساعة جديدة</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>الصورة</th>
                    <th>اسم الساعة</th>
                    <th>النوع</th>
                    <th>السعر</th>
                    <th>العمليات</th>
                </tr>
            </thead>
            <tbody>
                <?php
$result = mysqli_query($conn, "SELECT * FROM products");

if(mysqli_num_rows($result) > 0){
    while($row = mysqli_fetch_assoc($result)){
?>
<tr>
    <td>
        <img src="image/<?php echo $row['image']; ?>" class="product-img">
    </td>
    <td><?php echo $row['name']; ?></td>
    <td><?php echo $row['category']; ?></td>
    <td>€<?php echo $row['price']; ?></td>
    <td class="actions">
        <a href="delete.php?id=<?php echo $row['id']; ?>">
            <button class="btn-delete">حذف</button>
        </a>
    </td>
</tr>
<?php
    }
}else{
    echo "<tr><td colspan='5'>لا توجد منتجات</td></tr>";
}
?>
                
            </tbody>
        </table>
    </div>
</div>

</body>
</html>