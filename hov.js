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
  const theme = localStorage.getItem("userTheme");
  window.location.href = (theme === "brown") ? "loginBrown.html" : "login.html";
}

function goProducts() {
  const theme = localStorage.getItem("userTheme");
  window.location.href = (theme === "brown") ? "Sp.html" : "MprPage.html";
}

function goCart() {
  const theme = localStorage.getItem("userTheme");
  window.location.href = (theme === "brown") ? "cart2.html" : "cart.html";
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

// إضافة منتج
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
// تحديث رقم السلة فوق
function updateCartCount() {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    let count = 0;

    cart.forEach(item => {
        count += item.quantity;
    });

    let badge = document.querySelector(".badge");
    if (badge) badge.innerText = count;
}

// فتح صفحة الكارت
function goCart() {
    window.location.href = "cart.html";
}

// تحميل الكارت في الصفحة
 function loadCart() {
        let cart = JSON.parse(localStorage.getItem("cart"));
        let container = document.getElementById("cartItems");
        let totalDisplay = document.getElementById("totalPrice");

        if (!cart || cart.length === 0) {
            container.innerHTML = "<p class='empty-msg'>السلة فارغة حالياً..</p>";
            totalDisplay.style.display = "none";
            return;
        }

        let total = 0;
        container.innerHTML = ""; // تنظيف الحاوية قبل العرض

        cart.forEach(item => {
            total += item.price * item.quantity;

            // إنشاء الكرت بنفس كلاسات الستايل الجديد
            container.innerHTML += `
                <div class="cart-item">
                    <h3>${item.name}</h3>
                    <p>السعر الفردي: €${item.price}</p>
                    <p>الكمية: ${item.quantity}</p>
                    <p style="color: #fff; font-weight: bold; margin-top:10px;">
                        المجموع: €${(item.price * item.quantity).toFixed(2)}
                    </p>
                </div>
            `;
        });

        totalDisplay.innerText = "الإجمالي النهائي: €" + total.toFixed(2);
    }

    // loadCart();
// حذف منتج
function removeItem(index) {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];

    cart.splice(index, 1);

    localStorage.setItem("cart", JSON.stringify(cart));

    loadCart();
    updateCartCount();
}

// تحميل تلقائي
document.addEventListener("DOMContentLoaded", () => {
    updateCartCount();
    loadCart();
});
function sendOrder() {
    let name = prompt("ادخل اسمك");
    let phone = prompt("رقمك");
    let address = prompt("عنوانك");

    let total = document.getElementById("totalPrice").innerText.replace("€", "");

    let formData = new FormData();
    formData.append("name", name);
    formData.append("phone", phone);
    formData.append("address", address);
    formData.append("total", total);

    fetch("save_order.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(data => {
        alert(data);
        localStorage.removeItem("cart"); // تفريغ السلة
        location.reload();
    });
}
function openCheckout() {
    document.getElementById("popup").style.display = "block";
}

function closePopup() {
    document.getElementById("popup").style.display = "none";
}