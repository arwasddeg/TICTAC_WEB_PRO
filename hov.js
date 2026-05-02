/* ===================================================== */
/*        متغير للتحكم في الأنيميشن                     */
/* ===================================================== */
let isAnimating = false;

/* ===================================================== */
/*          بيانات الساعات                               */
/* ===================================================== */
const watches = {
  1: {
    img: "image/ex1.png",
    theme: "",
    tag: "ساعات حديثة فاخرة",
    code: "CH-3123-PABL",
    title: "تصميم عصري دقة لا مثيل له"
  },
  2: {
    img: "image/apple.png",
    theme: "theme-3",
    tag: "ساعة ذكية",
    code: "APL-WT-2025",
    title: "وقت ذكي<br>حياة أكثر تنظيم<br>وأداء يومي أفضل"
  }
};

/* ===================================================== */
/*        تغيير الساعة                                  */
/* ===================================================== */
function changeWatch(index, element) {
  if (isAnimating) return;
  isAnimating = true;

  const watch = document.getElementById("mainWatch");
  const tag   = document.getElementById("watchTag");
  const code  = document.getElementById("watchCode");
  const title = document.getElementById("watchTitle");
  const dots  = document.querySelectorAll(".pagination span");

  dots.forEach(dot => dot.classList.remove("active"));
  element.classList.add("active");

  watch.style.opacity = "0";

  setTimeout(() => {
    watch.src = watches[index].img;
    tag.innerHTML = watches[index].tag;
    code.innerHTML = watches[index].code;
    title.innerHTML = watches[index].title;
    document.body.className = watches[index].theme;

    if (watches[index].theme === "") localStorage.setItem("userTheme", "blue");
    else if (watches[index].theme === "theme-3") localStorage.setItem("userTheme", "brown");

    watch.style.opacity = "1";
    isAnimating = false;
  }, 400);
}

/* ===================================================== */
/*        ظهور البطاقات عند السكروول                     */
/* ===================================================== */
const cards = document.querySelectorAll('.card');
const observer = new IntersectionObserver(entries => {
  entries.forEach(entry => {
    if (entry.isIntersecting) entry.target.classList.add('show');
  });
}, { threshold: 0.1 });

cards.forEach(card => observer.observe(card));

/* ===================================================== */
/*          زر الإعجاب (القلب)                            */
/* ===================================================== */
function toggleFavorite(el) {
  el.classList.toggle("active");
  el.textContent = el.classList.contains("active") ? "❤" : "♡";
}

/* ===================================================== */
/*          الانتقال بين الصفحات حسب الثيم               */
/* ===================================================== */
function goLogin() {
  window.location.href = "login.html";
}

function goProducts() {
  const theme = localStorage.getItem("userTheme");
  window.location.href = (theme === "brown") ? "Sp.html" : "MprPage.html";
}

function goCart() {
  const theme = localStorage.getItem("userTheme");
  window.location.href = (theme === "brown") ? "cart2.html" : "cart.php";
}

/* ===================================================== */
/*          تفعيل الثيم عند تحميل الصفحة                */
/* ===================================================== */
window.onload = function() {
  const savedTheme = localStorage.getItem("userTheme");
  const watch = document.getElementById("mainWatch");
  const tag   = document.getElementById("watchTag");
  const code  = document.getElementById("watchCode");
  const title = document.getElementById("watchTitle");
  const dots  = document.querySelectorAll(".pagination span");

  if (savedTheme === "brown") {
    document.body.className = "theme-3";
    if(watch) watch.src = watches[2].img;
    if(tag) tag.innerHTML = watches[2].tag;
    if(code) code.innerHTML = watches[2].code;
    if(title) title.innerHTML = watches[2].title;
    dots.forEach(dot => dot.classList.remove("active"));
    if(dots[0]) dots[0].classList.add("active");
  } else {
    document.body.className = "";
    if(dots[1]) dots[1].classList.add("active");
  }
};

/* ===================================================== */
/*          زر الموبايل يفتح القائمة                     */
/* ===================================================== */
// زر الموبايل يفتح القائمة الكاملة
const menuIcon = document.querySelector('.menu-icon');
const navLinks = document.querySelector('.nav-links');

menuIcon.addEventListener('click', () => {
  navLinks.classList.toggle('active');
});

// Dropdown يعمل بالضغط على جميع الشاشات
document.querySelectorAll(".dropbtn").forEach(btn => {
  btn.addEventListener("click", function(e) {
    e.preventDefault(); // منع الرابط الافتراضي
    const dropdown = this.parentElement;
    
    // إغلاق أي dropdown آخر مفتوح
    document.querySelectorAll(".dropdown").forEach(d => {
      if(d !== dropdown) d.classList.remove("active");
    });

    // تبديل الحالة الحالية
    dropdown.classList.toggle("active");
  });
});

// لإغلاق الـ dropdown إذا ضغط المستخدم خارج القائمة
document.addEventListener("click", function(e) {
  if(!e.target.closest(".dropdown") && !e.target.closest(".menu-icon")) {
    document.querySelectorAll(".dropdown").forEach(d => d.classList.remove("active"));
  }
});

// // كود لفتح قائمة المنتجات المنسدلة عند الضغط في الموبايل
// document.querySelector('.dropbtn').addEventListener('click', function(e) {
//     if (window.innerWidth <= 768) {
//         e.preventDefault();
//         this.parentElement.classList.toggle('active');
//     }
// });

// ===================== CART SYSTEM =====================

// إضافة منتج للسلة
function addToCart(id, name, price) {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];

    let existing = cart.find(item => item.id === id);

    if (existing) {
        existing.quantity += 1;
    } else {
        cart.push({
            id: id,
            name: name,
            price: price,
            quantity: 1
        });
    }

    localStorage.setItem("cart", JSON.stringify(cart));

   

    updateCartCount();
}
// تحديث العداد
function updateCartCount() {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    let count = cart.reduce((acc, item) => acc + item.quantity, 0);
    let badge = document.querySelector(".badge");
    if (badge) badge.innerText = count;
}

// تحميل وعرض الكارت
function loadCart() {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    let container = document.getElementById("cartItems");
    let totalDisplay = document.getElementById("totalPrice"); // توحيد الـ ID

    if (!container) return; // لضمان عدم العمل في صفحات أخرى

    if (cart.length === 0) {
        container.innerHTML = "<p style='color:white; text-align:center;'>السلة فارغة حالياً..</p>";
        if (totalDisplay) totalDisplay.innerText = "€0.00";
        return;
    }

    let total = 0;
    container.innerHTML = cart.map((item, index) => {
        total += parseFloat(item.price) * parseInt(item.quantity);
        return `
            <div class="cart-card">
                <div class="product-info">
                    <h3>${item.name}</h3>
                    <p>السعر: €${item.price} | الكمية: ${item.quantity}</p>
                </div>
                <button class="remove-btn" onclick="removeItem(${index})">حذف</button>
            </div>
        `;
    }).join('');

    if (totalDisplay) totalDisplay.innerText = "€" + total.toFixed(2);
}

// حذف منتج
function removeItem(index) {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    cart.splice(index, 1);
    localStorage.setItem("cart", JSON.stringify(cart));
    loadCart();
    updateCartCount();
}

// معالجة الطلب وإرساله للداتابيز
function processOrder() {
    let name = document.getElementById("custName").value;
    let phone = document.getElementById("custPhone").value;
    let address = document.getElementById("custAddress").value;
    // جلب الإجمالي من العنصر الموحد
    let totalElement = document.getElementById("totalPrice");
    let totalText = totalElement ? totalElement.innerText.replace("€", "") : "0";

    if (!name || !phone || !address) return alert("يرجى ملء جميع البيانات");

    let formData = new FormData();
    formData.append("name", name);
    formData.append("phone", phone);
    formData.append("address", address);
    formData.append("total", totalText);

    fetch("save_order.php", { method: "POST", body: formData })
    .then(res => res.text())
    .then(data => {
        alert("تم استلام طلبك بنجاح!");
        localStorage.removeItem("cart");
        window.location.href = "index.html";
    })
    .catch(err => console.error("Error:", err));
}

// تنفيذ الأوامر عند تحميل الصفحة
document.addEventListener("DOMContentLoaded", () => {
    updateCartCount();
    if (document.getElementById("cartItems")) {
        loadCart();
    }
});

// وظائف فتح وإغلاق الـ Popup


function openCheckout() {

    let cart = JSON.parse(localStorage.getItem("cart")) || [];

    if (cart.length === 0) {
        alert("السلة فارغة!");
        return;
    }

    window.location.href = "check_login.php";
}

function closePopup() {
    document.getElementById("popup").style.display = "none";
}
function handleAuth() {
    const userVal = document.getElementById("username").value;
    const passVal = document.getElementById("password").value;
    const addressVal = document.getElementById("address") ? document.getElementById("address").value : "";
    
    // جلب بيانات السلة من الـ LocalStorage
    const cart = JSON.parse(localStorage.getItem("cart")) || [];

    if (!userVal) {
        alert("الرجاء إدخال اسم المستخدم أو الرقم");
        return;
    }

    // تجهيز البيانات للإرسال لـ PHP
    let formData = new FormData();
    formData.append("user", userVal);
    formData.append("pass", passVal);
    formData.append("address", addressVal);
    formData.append("cartData", JSON.stringify(cart));

    // إرسال البيانات لملف المعالجة (الذي سننشئه باسم check_auth.php)
    fetch("check_auth.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === "admin") {
            alert("مرحباً أيها المسؤول!");
            window.location.href = "admin_dashboard.php";
        } else if (data.status === "success_order") {
            alert("تم تسجيل حسابك وتأكيد طلبك بنجاح!");
            localStorage.removeItem("cart"); // تفريغ السلة بعد النجاح
            window.location.href = "index.html";
        } else {
            alert(data.message);
        }
    })
    .catch(err => console.error("Error:", err));
}

// دالة ذكية لإظهار حقل العنوان إذا لم يكن أدمن
document.getElementById("username").addEventListener("input", function() {
    const extra = document.getElementById("extraFields");
    if (this.value !== "admin") {
        extra.style.display = "block";
    } else {
        extra.style.display = "none";
    }
});