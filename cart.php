<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>سلة المشتريات - TICTAC</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background: radial-gradient(circle at center, #0a1f33 0%, #020a10 100%);
            color: #ffffff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            direction: rtl;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        h1 {
            font-size: 2.5rem;
            margin-top: 100px;
            margin-bottom: 30px;
            text-shadow: 0 0 10px rgba(255,255,255,0.2);
        }
        #cartContainer {
            width: 100%;
            max-width: 800px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            overflow: hidden;
            font-family: 'Times New Roman', Times, serif;
        }
        .cart-header {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 0.8fr;
            padding: 15px 20px;
            background: rgba(0, 0, 0, 0.3);
            font-weight: bold;
            color: #00d4ff;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            align-items: center;
        }
        .cart-header span:nth-child(3),
        .cart-header span:nth-child(4) {
            text-align: center;
        }
        .cart-item {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 0.8fr;
            align-items: center;
            background: rgba(255, 255, 255, 0.05);
            padding: 15px 20px;
            margin-bottom: 5px;
            transition: transform 0.2s;
        }
        .cart-item:hover {
            transform: translateY(-2px);
            background: rgba(255, 255, 255, 0.08);
        }
        .item-name {
            font-size: 1.1rem;
            text-align: right;
        }
        .item-price {
            text-align: right;
            font-size: 1.1rem;
        }
        .quantity-control {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 2px 5px;
            justify-content: space-between;
            max-width: 100px;
            margin: 0 auto;
        }
        .quantity-control button {
            background: transparent;
            border: none;
            color: white;
            width: 25px;
            height: 25px;
            cursor: pointer;
            font-size: 18px;
        }
        .quantity-control button:hover {
            color: #00d4ff;
        }
        .remove-btn {
            background: rgba(255, 68, 68, 0.15);
            color: #ff4d4d;
            border: none;
            padding: 8px 15px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            text-align: center;
            width: fit-content;
            margin: 0 auto;
        }
        .remove-btn:hover {
            background: rgba(255, 68, 68, 0.3);
            color: #ff6666;
        }
        #totalPrice {
            margin-top: 30px;
            padding: 15px 30px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50px;
            font-size: 1.5rem;
        }
        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }
        .checkout-btn, .continue-btn {
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: transform 0.2s;
            text-decoration: none;
            display: inline-block;
        }
        .checkout-btn {
            background: #25D366;
            color: white;
        }
        .checkout-btn:hover {
            transform: translateY(-2px);
            background: #128C7E;
        }
        .continue-btn {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .continue-btn:hover {
            transform: translateY(-2px);
            background: rgba(255, 255, 255, 0.2);
        }
        .empty-cart {
            text-align: center;
            padding: 60px;
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.6);
        }
        @media (max-width: 600px) {
            .cart-header, .cart-item {
                grid-template-columns: 2fr 1fr 1fr 0.8fr;
                font-size: 12px;
                gap: 5px;
            }
            .quantity-control button {
                width: 25px;
                height: 25px;
                font-size: 14px;
            }
            .checkout-btn, .continue-btn {
                padding: 8px 16px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

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
              <a href="MprPage.php">ساعات حديثة</a>
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

<h1>🛒 سلة المشتريات</h1>

<div id="cartContainer">
    <div class="cart-header">
        <span>المنتج</span>
        <span>السعر</span>
        <span>الكمية</span>
        <span>الإجراء</span>
    </div>
    <div id="cartItemsList"></div>
</div>

<h2 id="totalPrice">الإجمالي: </h2>

<div class="button-group">
    <button class="checkout-btn" onclick="goToCheckout()">
        📱 إتمام الطلب عبر واتساب
    </button>
    <a href="index.html" class="continue-btn">
        🛍️ متابعة التسوق
    </a>
</div>

<script>
// دالة تحميل وعرض السلة
function renderCart() {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    let container = document.getElementById("cartItemsList");
    let totalDisplay = document.getElementById("totalPrice");

    if (cart.length === 0) {
        container.innerHTML = '<div class="empty-cart">🛒 السلة فارغة<br><br><a href="index.html" style="color:#00d4ff;">تسوق الآن</a></div>';
        totalDisplay.innerText = "💰 الإجمالي: €0";
        return;
    }

    let total = 0;
    container.innerHTML = "";

    cart.forEach((item, index) => {
        let subtotal = item.price * item.quantity;
        total += subtotal;

        container.innerHTML += `
            <div class="cart-item" data-index="${index}">
                <span class="item-name">${item.name}</span>
                <span class="item-price">€${item.price}</span>
                <div class="quantity-control">
                    <button onclick="changeQty(${index}, -1)">-</button>
                    <span id="qty-${index}">${item.quantity}</span>
                    <button onclick="changeQty(${index}, 1)">+</button>
                </div>
                <button class="remove-btn" onclick="deleteCartItem(${index})">حذف 🗑️</button>
            </div>
        `;
    });

    totalDisplay.innerHTML = "الإجمالي: <span style='color: #00d4ff;'>€" + total.toFixed(2) + "</span>";
}

// تحديث الكمية
function changeQty(index, change) {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    if (cart[index]) {
        let newQty = cart[index].quantity + change;
        if (newQty > 0) {
            cart[index].quantity = newQty;
        } else {
            cart.splice(index, 1);
        }
        localStorage.setItem("cart", JSON.stringify(cart));
        renderCart();
        updateCartBadge();
    }
}

// حذف منتج
function deleteCartItem(index) {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    cart.splice(index, 1);
    localStorage.setItem("cart", JSON.stringify(cart));
    renderCart();
    updateCartBadge();
}

// التوجه لإتمام الطلب
function goToCheckout() {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];

    if (cart.length === 0) {
        alert("⚠️ السلة فارغة!");
        return;
    }

    window.location.href = "check_login.php";
}

// تحديث عداد السلة
function updateCartBadge() {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    let totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    
    let cartBadge = document.getElementById("cart-badge");
    if (cartBadge) {
        cartBadge.innerText = totalItems;
        cartBadge.style.display = totalItems > 0 ? "inline-block" : "none";
    }
}

// تشغيل الدالة عند فتح الصفحة
document.addEventListener('DOMContentLoaded', function() {
    renderCart();
    updateCartBadge();
});

// تحديث العداد من أي نافذة أخرى
window.addEventListener('storage', function(e) {
    if (e.key === 'cart') {
        renderCart();
        updateCartBadge();
    }
});

// دالة للاستدعاء من الصفحات الأخرى
window.updateCartBadge = updateCartBadge;
</script>

<footer class="footer">
  <div class="footer-header">
    <h2>TICTAC</h2>
  </div>

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

  <div class="footer-bottom">
    © 2025 جميع الحقوق محفوظة
  </div>
</footer>

<script src="hov.js"></script>
</body>
</html>