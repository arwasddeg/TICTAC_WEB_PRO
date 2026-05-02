<?php 
include 'config.php'; 
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="MprPage.css">
    <title>ساعات حديثة | TICTAC</title>
</head>
<body style="background: linear-gradient(to right bottom, #2b6081, #14425f, #030f16, #03111e, #031f30, #3065a5, #2e4968); background-size: cover; background-attachment: fixed;">

<header class="navbar">
    <div class="nav-right-group">
        <div class="logo">TIC<span>TAC</span></div>
        <div class="menu-icon">☰</div>
    </div>

    <nav class="nav-links">
        <a href="index.html">الرئيسية</a>
        <div class="dropdown">
            <a href="javascript:void(0)" class="dropbtn">المنتجات ▾</a>
            <div class="dropdown-content">
                <a href="MprPage.php?type=modern">ساعات حديثة</a>
            </div>
        </div>
        <a href="aboutUs.html">من نحن</a>
    </nav>

    <div class="nav-left-icons">
        <div class="icon-group">
            <div class="icon-item" onclick="location.href='cart.php'">🛒 <span class="badge" id="cart-badge">0</span></div>
            <div class="icon-item" onclick="location.href='login.html'">👤</div>
            <div class="icon-item">♡ <span class="badge" id="fav-badge">0</span></div>
        </div>
    </div>
</header>

<section class="page">
    <h1>ساعات حديثة</h1>
    
    <div class="filter-buttons">
        <button onclick="filterSelection('all')" class="btn">الكل</button>
        <button onclick="filterSelection('men')" class="btn">رجالي</button>
        <button onclick="filterSelection('women')" class="btn">نسائي</button> 
    </div>

    <div class="grid">
    <?php
    // جلب المنتجات حسب النوع (modern)
    $query = "SELECT * FROM products WHERE category = 'modern' ORDER BY id DESC";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_assoc($result)){
            // نستخدم حقل الوصف أو حقول مخصصة للمواصفات إذا وجدت في قاعدة البيانات
            // هنا وضعت قيم افتراضية، يمكنك استبدالها ببيانات من قاعدة البيانات
            $category_type = (strpos($row['name'], 'نسائي') !== false) ? 'women' : 'men'; 
    ?>
            <!-- الرابط يفتح الـ Modal الخاص بالمنتج -->
            <a href="#product<?php echo $row['id']; ?>" style="text-decoration: none; color: inherit;">
                <div class="card" 
                     data-category="<?php echo $category_type; ?>" 
                     data-battery="48 ساعة" 
                     data-water="مقاومة للماء 50m" 
                     data-heart="متوفر" 
                     data-gps="متوفر"
                     onmouseover="showDetails(this)" 
                     onmouseout="closeDetails()">
                    
                    <div class="img-box">
                        <img src="image/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                    </div>
                    <h3><?php echo $row['name']; ?></h3>
                    <span>€<?php echo number_format($row['price'], 2); ?></span>
                    
                    <!-- الزر يستدعي دالة الإضافة للسلة من ملف hov.js -->
                    <button onclick="event.preventDefault(); addToCart(<?php echo $row['id']; ?>, '<?php echo addslashes($row['name']); ?>', <?php echo $row['price']; ?>)">
                        أضف إلى السلة
                    </button>
                </div>
            </a>
    <?php
        }
    } else {
        echo "<p style='color:white;'>لا توجد ساعات حالياً في هذا القسم.</p>";
    }
    ?>
    </div>
</section>

<!-- لوحة التفاصيل الجانبية (تظهر عند تمرير الماوس) -->
<div id="sidePanel" class="side-panel">
    <div class="panel-content">
        <img id="panelImg" src="" alt="">
        <h2 id="panelTitle"></h2>
        <p id="panelSubtitle"></p>
        <hr>
        <div class="details-text">
            <h4>المواصفات التقنية</h4> 
            <ul>
                <li id="pBattery"></li>
                <li id="pWater"></li>
                <li id="pHeart"></li>
                <li id="pGPS"></li>
            </ul>
        </div>
    </div>
</div>

<!-- إنشاء الـ Modals لكل ساعة بشكل ديناميكي -->
<?php
if(mysqli_num_rows($result) > 0) {
    mysqli_data_seek($result, 0); 
    while($row = mysqli_fetch_assoc($result)) {
        $productId = $row['id'];
?>
        <div id="product<?php echo $productId; ?>" class="modal-css">
            <div class="modal-content">
                <a href="#" class="close-btn">&times;</a>
                <div class="modal-layout">
                    <div class="gallery-section">
                        <img src="image/<?php echo $row['image']; ?>" class="main-img">
                    </div>
                    <div class="info-section">
                        <h2><?php echo $row['name']; ?></h2>
                        <h3>€<?php echo number_format($row['price'], 2); ?></h3>
                        <div class="desc">
                            <strong>الوصف والمميزات:</strong>
                            <p><?php echo nl2br($row['description']); ?></p>
                        </div>
                        <button class="cart-btn" onclick="addToCart(<?php echo $row['id']; ?>, '<?php echo addslashes($row['name']); ?>', <?php echo $row['price']; ?>)">
                            أضف إلى السلة
                        </button>
                    </div>
                </div>
            </div>
        </div>
<?php
    }
}
?>

<script>
    // وظيفة عرض التفاصيل الجانبية
    function showDetails(element) {
        document.getElementById('panelImg').src = element.querySelector('img').src;
        document.getElementById('panelTitle').innerText = element.querySelector('h3').innerText;
        document.getElementById('panelSubtitle').innerText = element.querySelector('span').innerText;

        document.getElementById('pBattery').innerHTML = "🔋 الطاقة: " + element.getAttribute('data-battery');
        document.getElementById('pWater').innerHTML = "✨ المواد: " + element.getAttribute('data-water');
        document.getElementById('pHeart').innerHTML = "🛡️ الحماية: " + element.getAttribute('data-heart');
        document.getElementById('pGPS').innerHTML = "⚓ الإطار: " + element.getAttribute('data-gps');

        document.getElementById('sidePanel').classList.add('active');
    }

    function closeDetails() {
        document.getElementById('sidePanel').classList.remove('active');
    }

    // وظيفة الفلترة (رجالي / نسائي)
    function filterSelection(category) {
        let cards = document.querySelectorAll(".card");
        cards.forEach(card => {
            let cardCategory = card.getAttribute("data-category");
            let parentLink = card.parentElement; // الرابط الأب <a>

            if (category === "all" || cardCategory === category) {
                parentLink.style.display = "block";
            } else {
                parentLink.style.display = "none";
            }
        });
    }
</script>

<footer class="footer">
    <div class="footer-header"><h2>TICTAC</h2></div>
    <div class="footer-nav">
        <div class="footer-link">
            <h3>معلومات</h3>
            <ul>
                <li><a href="aboutUs.html">من نحن</a></li>
                <li><a href="aboutUs.html">أسئلة متكررة</a></li>
                <li><a href="aboutUs.html">إتصل بنا</a></li>
            </ul>
        </div>
        <div class="footer-link">
            <h3>الشروط والسياسات</h3>
            <ul>
                <li><a href="#">شروط الاستخدام</a></li>
                <li><a href="#">سياسة الخصوصية</a></li>
            </ul>
        </div>
        <div class="footer-link">
            <h3>العنوان</h3>
            <p>بن عاشور - طرابلس</p>
        </div>
        <div class="footer-link">
            <h3>معلومات الوصول</h3>
            <ul>
                <li>Tictac2025@gmail.com</li>
                <li>0915044592</li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">© 2025 جميع الحقوق محفوظة</div>
</footer>

<script src="hov.js">
    function updateCartBadge() {
    // 1. تحديث رقم السلة
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    let totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    
    let cartBadge = document.getElementById("cart-badge");
    if (cartBadge) {
        cartBadge.innerText = totalItems;
    }


    // 2. تحديث رقم المفضلة (إذا كان لديك منطق للمفضلة)
    let favorites = JSON.parse(localStorage.getItem("favorites")) || [];
    let favBadge = document.getElementById("fav-badge");
    if (favBadge) {
        favBadge.innerText = favorites.length; // يعرض عدد العناصر المميزة بقلب
    }
}

// تأكدي من تشغيل الدالة عند تحميل الصفحة
window.addEventListener('DOMContentLoaded', updateCartBadge);
</script>
</body>
</html>