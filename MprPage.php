<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="MprPage.css">
    <title>Sport Watches</title>
</head>
<body style="
background: linear-gradient(to right bottom,
    #2b6081,
    #14425f,
    #030f16 ,
    #03111e,
    #031f30,
    #3065a5,
    #2e4968
   );
    background-size: cover;
    background-attachment: fixed;
">
 <header class="navbar">

  <!-- الجهة اليمنى: شعار + زر الموبايل -->
  <div class="nav-right-group">
      <div class="logo">TIC<span>TAC</span></div>
      <div class="menu-icon">☰</div>
  </div>

  <!-- الروابط -->
  <nav class="nav-links">
      <a href="index.html">الرئيسية</a>

      <!-- Dropdown المنتجات -->
      <div class="dropdown">
          <a href="javascript:void(0)" class="dropbtn">المنتجات ▾</a>
          <div class="dropdown-content">
              <a href="MprPage.html?type=modern">ساعات حديثة</a>
              <a href="Sp.html?type=sport">ساعات رياضية</a>
          </div>
      </div>

     <a href="aboutUs.html">من نحن</a>

  </nav>

  <!-- الأيقونات -->
  <div class="nav-left-icons">
      <div class="icon-group">
         <div class="icon-item" onclick="goCart()">🛒 <span class="badge">0</span></div>
          <div class="icon-item" onclick="goLogin()">👤</div>
          <div class="icon-item">♡ <span class="badge">8</span></div>
      </div>
  </div>

</header>

<section class="page">
  <h1>ساعات حديثة</h1>
<div class="filter-buttons">
    <div class="filter-buttons">
    <button onclick="filterSelection('all')" class="btn">الكل</button>
    <button onclick="filterSelection('men')" class="btn">رجالي</button>
     <button onclick="filterSelection('women')" class="btn">نسائي</button> 
     
     
    </div>
</div>
<div class="grid">
<?php
$query = "SELECT * FROM products WHERE category = 'modern' ORDER BY id DESC";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) > 0){
    while($row = mysqli_fetch_assoc($result)){
?>
    <div class="card">
        <div class="img-box">
            <img src="image/<?php echo $row['image']; ?>">
        </div>
        <h3><?php echo $row['name']; ?></h3>
        <span>€<?php echo $row['price']; ?></span>
<button onclick='addToCart(
    <?php echo $row["id"]; ?>,
    <?php echo json_encode($row["name"]); ?>,
    <?php echo $row["price"]; ?>
)'>
أضف إلى السلة
</button>

    </div>
<?php
    }
}else{
    echo "<p>لا توجد ساعات حالياً</p>";
}
?>
</div>
</section>

<div id="sidePanel" class="side-panel">
    <div class="panel-content">
        <img id="panelImg" src="" alt="">
        <h2 id="panelTitle"></h2>
        <p id="panelSubtitle"></p>
        <hr>
<div class="details-text">
    <h4>المواصفات التقنية</h4> <ul>
        <li id="pBattery"></li>
        <li id="pWater"></li>
        <li id="pHeart"></li>
        <li id="pGPS"></li>
    </ul>
</div>
    </div>
</div>
<?php
// إعادة تنفيذ حلقة التكرار لإنشاء الـ Modals الخاصة بكل ساعة بشكل ديناميكي
mysqli_data_seek($result, 0); // إعادة المؤشر للبداية
while($row = mysqli_fetch_assoc($result)) {
    $productId = $row['id'];
    $image = "image/" . $row['image'];
    ?>
    <div id="product<?php echo $productId; ?>" class="modal-css">
        <div class="modal-content">
            <a href="#" class="close-btn">&times;</a>
            <div class="modal-layout">
                <div class="gallery-section">
                    <img src="<?php echo $image; ?>" class="main-img">
                </div>
                <div class="info-section">
                    <h2><?php echo $row['name']; ?></h2>
                    <h3>€<?php echo number_format($row['price'], 2); ?></h3>
                    <div class="desc">
                        <p>
                            <br>
                            <dl>الوصف والمميزات</dl>
                            <dt><?php echo $row['description']; ?></dt>
                        </p>
                    </div>
                    <button class="cart-btn">أضف إلى السلة</button>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>
<script>
    function showDetails(element) {
    // جلب النصوص والصورة
    document.getElementById('panelImg').src = element.querySelector('img').src;
    document.getElementById('panelTitle').innerText = element.querySelector('h3').innerText;
    document.getElementById('panelSubtitle').innerText = element.querySelector('span').innerText;

    // جلب المواصفات (تأكدي أن المسميات مطابقة للـ data- في الأعلى)
    const battery = element.getAttribute('data-battery');
    const water = element.getAttribute('data-water');
    const heart = element.getAttribute('data-heart');
    const gps = element.getAttribute('data-gps');

// تعبئة الـ <li> في الـ HTML
    document.getElementById('pBattery').innerHTML = "🔋 الطاقة: " + battery;
    document.getElementById('pWater').innerHTML = "✨ المواد: " + water;
    document.getElementById('pHeart').innerHTML = "🛡️ الحماية: " + heart;
    document.getElementById('pGPS').innerHTML = "⚓ الإطار: " + gps;

    document.getElementById('sidePanel').classList.add('active');
}
function filterSelection(category) {
    let cards = document.querySelectorAll(".card"); // جلب كل الكاردات
    
    cards.forEach(card => {
        let cardCategory = card.getAttribute("data-category");
        let parentLink = card.closest('a'); // الوصول للرابط الأب <a>

        if (category === "all" || cardCategory === category) {
            parentLink.style.display = "block"; // إظهار إذا كان مطابقاً أو اخترنا الكل
        } else {
            parentLink.style.display = "none";  // إخفاء إذا لم يكن مطابقاً
        }
    });
}

    function closeDetails() {
        // إخفاء اللوحة عند ابتعاد الماوس
        document.getElementById('sidePanel').classList.remove('active');
    }
</script>
<footer class="footer">
  <!-- العنوان الكبير -->
  <div class="footer-header">
    <h2>TICTAC</h2>
  </div>

  <!-- أسماء العناصر في صف واحد -->
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
        <li><a href="#">Tictac2025@gmail.com</a></li>
        <li><a href="#">0915044592</a></li>
        <li><a href="#">0925044592</a></li>
      </ul>
    </div>
  </div>

  <!-- نص أسفل الفوتر -->
  <div class="footer-bottom">
    © 2025 جميع الحقوق محفوظة
  </div>
</footer>
 <!-- ملف الجافاسكريبت -->
    <script src="hov.js"></script>
    
</body>
</html>