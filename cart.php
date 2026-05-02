<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>سلة المشتريات - TICTAC</title>
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
            margin-bottom: 30px;
            text-shadow: 0 0 10px rgba(255,255,255,0.2);
        }
        #cartItems {
            width: 100%;
            max-width: 800px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            overflow: hidden;
        }
        .cart-header, .cart-item {
            display: grid;
            grid-template-columns: 3fr 1fr 1fr 1fr;
            padding: 15px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .cart-header {
            background: rgba(0, 0, 0, 0.3);
            font-weight: bold;
            color: #00d4ff;
        }
        .cart-item {
            transition: background 0.2s;
        }
        .cart-item:hover {
            background: rgba(255, 255, 255, 0.05);
        }
        .quantity-control {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .quantity-control button {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            transition: all 0.2s;
        }
        .quantity-control button:hover {
            background: #00d4ff;
            color: black;
        }
        .remove-btn {
            background: rgba(255, 68, 68, 0.2);
            color: #ff4444;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .remove-btn:hover {
            background: #ff4444;
            color: white;
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
                grid-template-columns: 2fr 1fr 1fr 0.5fr;
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

<h1>🛒 سلة المشتريات</h1>

<div id="cartItems">
    <div class="cart-header">
        <span>المنتج</span>
        <span>السعر</span>
        <span>الكمية</span>
        <span>الإجراء</span>
    </div>
    <div id="cartItemsList"></div>
</div>

<h2 id="totalPrice"></h2>

<div class="button-group">
    <button class="checkout-btn" onclick="proceedToCheckout()">
        📱 إتمام الطلب عبر واتساب
    </button>
    <a href="index.html" class="continue-btn">
        🛍️ متابعة التسوق
    </a>
</div>

<script>
// دالة تحميل وعرض السلة
function loadCart() {
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
                <span>${item.name}</span>
                <span>€${item.price}</span>
                <div class="quantity-control">
                    <button onclick="updateQuantity(${index}, -1)">-</button>
                    <span id="qty-${index}">${item.quantity}</span>
                    <button onclick="updateQuantity(${index}, 1)">+</button>
                </div>
                <button class="remove-btn" onclick="removeItem(${index})">🗑️ حذف</button>
            </div>
        `;
    });

    totalDisplay.innerText = "💰 الإجمالي: €" + total.toFixed(2);
}

// تحديث الكمية
function updateQuantity(index, change) {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    if (cart[index]) {
        let newQty = cart[index].quantity + change;
        if (newQty > 0) {
            cart[index].quantity = newQty;
        } else {
            cart.splice(index, 1);
        }
        localStorage.setItem("cart", JSON.stringify(cart));
        loadCart();
        updateCartBadge();
    }
}

// حذف منتج
function removeItem(index) {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    cart.splice(index, 1);
    localStorage.setItem("cart", JSON.stringify(cart));
    loadCart();
    updateCartBadge();
}

// التوجه لإتمام الطلب
function proceedToCheckout() {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    
    if (cart.length === 0) {
        alert("⚠️ السلة فارغة! أضف بعض المنتجات أولاً.");
        return;
    }
    
    // التحقق من تسجيل الدخول
    <?php if(!isset($_SESSION['user_id']) && !isset($_SESSION['email'])): ?>
        if(confirm("⚠️ يجب تسجيل الدخول أولاً لإتمام الطلب. هل تريد الذهاب لصفحة التسجيل؟")) {
            window.location.href = "login.html";
        }
        return;
    <?php else: ?>
        window.location.href = "send_to_whatsapp.php";
    <?php endif; ?>
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
    loadCart();
    updateCartBadge();
});

// تحديث العداد من أي نافذة أخرى
window.addEventListener('storage', function(e) {
    if (e.key === 'cart') {
        loadCart();
        updateCartBadge();
    }
});

// دالة للاستدعاء من الصفحات الأخرى
window.updateCartBadge = updateCartBadge;
</script>

</body>
</html>