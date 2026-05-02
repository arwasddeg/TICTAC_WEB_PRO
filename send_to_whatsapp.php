<?php
session_start();

// التأكد من أن المستخدم سجل دخوله
if (!isset($_SESSION['user_id']) && !isset($_SESSION['email'])) {
    header("Location: login.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إتمام الطلب - TICTAC</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            background: radial-gradient(circle at center, #0a1f33 0%, #020a10 100%);
            color: #ffffff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            direction: rtl;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            width: 90%;
            max-width: 500px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }
        .spinner {
            width: 50px;
            height: 50px;
            border: 4px solid rgba(255, 255, 255, 0.2);
            border-top: 4px solid #25D366;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .message {
            font-size: 18px;
            margin: 20px 0;
            line-height: 1.6;
        }
        .success {
            color: #25D366;
        }
        .error {
            color: #ff4444;
        }
        .button-group {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
        }
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: transform 0.2s,
           
        }
        .btn:hover {
            transform: translateY(-2px);
        }
        .btn-primary {
            background: #25D366;
            color: white;
        }
        .btn-primary:hover {
            background: #128C7E;
        }
        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        .order-summary {
            background: rgba(0, 0, 0, 0.3);
            border-radius: 12px;
            padding: 15px;
            margin: 20px 0;
            text-align: right;
            max-height: 300px;
            overflow-y: auto;
        }
        .order-summary h4 {
            color: #00d4ff;
            margin-bottom: 10px;
        }
        .order-item {
            padding: 8px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 14px;
        }
        .total {
            font-size: 18px;
            font-weight: bold;
            color: #00d4ff;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 2px solid rgba(255, 255, 255, 0.2);
        }
        .whatsapp-btn {
            background: #25D366;
            color: white;
            border: none;
        }
        .whatsapp-btn:hover {
            background: #128C7E;
        }
    </style>
</head>
<body>
    <div class="container">
        <div id="loading-state">
            <div class="spinner"></div>
            <div class="message">جاري تجهيز طلبك...</div>
        </div>
        
        
        <div id="success-state" style="display: none;">
            <div style="font-size: 60px;">✅</div>
            <div class="message success">تم تجهيز طلبك بنجاح!</div>
            <div class="message">سيتم فتح واتساب لإتمام عملية الدفع</div>
            <div class="button-group">
                <button onclick="redirectToWhatsApp()" class="btn btn-primary whatsapp-btn">📱 فتح واتساب</button>
                <a href="cart.php" class="btn btn-secondary">🛒 العودة للسلة</a>
            </div>
        </div>
        <!-- إضافة حقل "رقم الهاتف" و "العنوان" للرسالة -->
        <div style="margin-top:20px; text-align:right;">
    <label>📱 رقم الهاتف:</label>
    <input type="text" id="customerPhone" placeholder="مثال: 091xxxxxxx" style="width:100%; padding:10px; margin-bottom:10px; border-radius:8px;">

    <label>📍 العنوان:</label>
    <input type="text" id="customerAddress" placeholder="مثال: طرابلس - بن عاشور" style="width:100%; padding:10px; border-radius:8px;">
</div>
        <div id="error-state" style="display: none;">
            <div style="font-size: 60px;">⚠️</div>
            <div class="message error">السلة فارغة!</div>
            <div class="message">لا توجد منتجات لإتمام الطلب</div>
            <div class="button-group">
                <a href="index.html" class="btn btn-primary">🛍️ التسوق الآن</a>
                <a href="cart.php" class="btn btn-secondary">🛒 السلة</a>
            </div>
        </div>
        
        <div id="summary-state" style="display: none;">
            <div class="order-summary" id="order-summary"></div>
            <div class="button-group">
                <button onclick="confirmAndSend()" class="btn btn-primary whatsapp-btn">✅ تأكيد الطلب وإرسال للواتساب</button>
                <a href="cart.php" class="btn btn-secondary">✏️ تعديل الطلب</a>
            </div>
        </div>
    </div>

    <script>
        const whatsappNumber = "218919233764";
        const userEmail = "<?php echo isset($_SESSION['email']) ? addslashes($_SESSION['email']) : 'غير محدد'; ?>";
        const userId = "<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'غير محدد'; ?>";
        
        let currentCart = [];
        
        function loadCart() {
            try {
                currentCart = JSON.parse(localStorage.getItem("cart")) || [];
                
                if (currentCart.length === 0) {
                    document.getElementById('loading-state').style.display = 'none';
                    document.getElementById('error-state').style.display = 'block';
                } else {
                    displayOrderSummary();
                }
            } catch(e) {
                console.error('خطأ في قراءة السلة:', e);
                document.getElementById('loading-state').style.display = 'none';
                document.getElementById('error-state').style.display = 'block';
            }
        }
        
        function displayOrderSummary() {
            let summaryHtml = '<h4>📦 ملخص الطلب</h4>';
            let total = 0;
            
            currentCart.forEach((item, index) => {
                let subtotal = item.price * item.quantity;
                total += subtotal;
                summaryHtml += `
                    <div class="order-item">
                        <div><strong>${index + 1}.</strong> ${item.name}</div>
                        <div style="display: flex; justify-content: space-between; margin-top: 5px;">
                            <span>الكمية: ${item.quantity}</span>
                            <span>السعر: €${item.price}</span>
                            <span>المجموع: €${subtotal.toFixed(2)}</span>
                        </div>
                    </div>
                `;
            });
            
            summaryHtml += `<div class="total">💰 الإجمالي الكلي: €${total.toFixed(2)}</div>`;
            document.getElementById('order-summary').innerHTML = summaryHtml;
            
            document.getElementById('loading-state').style.display = 'none';
            document.getElementById('summary-state').style.display = 'block';
        }
        
        function prepareWhatsAppMessage() {

    let phone = document.getElementById("customerPhone").value;
    let address = document.getElementById("customerAddress").value;

    if (!phone || !address) {
        alert("⚠️ لازم تعبي رقم الهاتف والعنوان");
        return null;
    }

    let message = "🛒 *طلب جديد من متجر TICTAC*\n\n";
    message += "📧 *البريد:* " + userEmail + "\n";
    message += "📱 *الهاتف:* " + phone + "\n";
    message += "📍 *العنوان:* " + address + "\n";
    message += "━━━━━━━━━━━━━━━━━━━━\n";
    message += "*المنتجات:*\n\n";

    let total = 0;

    currentCart.forEach((item, index) => {
        let subtotal = item.price * item.quantity;
        total += subtotal;

        message += `${index + 1}. ${item.name}\n`;
        message += `الكمية: ${item.quantity}\n`;
        message += `السعر: €${item.price}\n\n`;
    });

    message += "━━━━━━━━━━━━━━━━━━━━\n";
    message += `💰 الإجمالي: €${total.toFixed(2)}\n`;

    return message;
}
        
        function saveOrderToDatabase() {
            let orderData = {
                user_id: userId,
                user_email: userEmail,
                items: currentCart,
                total: currentCart.reduce((sum, item) => sum + (item.price * item.quantity), 0),
                date: new Date().toISOString()
            };
            
            // حفظ نسخة في localStorage للمراجعة
            let orders = JSON.parse(localStorage.getItem('orders_history')) || [];
            orders.push({
                ...orderData,
                order_id: Date.now(),
                order_date: new Date().toLocaleString('ar-EG')
            });
            localStorage.setItem('orders_history', JSON.stringify(orders));
            
            // إرسال الطلب لـ save_order.php لحفظه في قاعدة البيانات
            fetch('save_order.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(orderData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('تم حفظ الطلب في قاعدة البيانات برقم:', data.order_id);
                } else {
                    console.log('خطأ في حفظ الطلب:', data.message);
                }
            })
            .catch(e => console.log('خطأ في حفظ الطلب:', e));
        }
        
        function redirectToWhatsApp() {

    const message = prepareWhatsAppMessage();

    if (!message) return; // لو ما عباش البيانات

    const encodedMessage = encodeURIComponent(message);
    const whatsappUrl = `https://wa.me/${whatsappNumber}?text=${encodedMessage}`;

    saveOrderToDatabase();
    localStorage.removeItem("cart");

    window.open(whatsappUrl, '_blank');

    document.getElementById('summary-state').style.display = 'none';
    document.getElementById('success-state').style.display = 'block';
}
        
        function updateCartBadgeInOpener() {
            if (window.opener && !window.opener.closed) {
                try {
                    if (typeof window.opener.updateCartBadge === 'function') {
                        window.opener.updateCartBadge();
                    }
                } catch(e) {}
            }
            
            // تحديث في localStorage لجميع النوافذ
            localStorage.setItem('cart_updated', Date.now().toString());
        }
        
        function confirmAndSend() {
            if (currentCart.length > 0) {
                redirectToWhatsApp();
            } else {
                location.reload();
            }
        }
        
        // بدء التحميل
        loadCart();
        
        // الاستماع لتحديثات localStorage
        window.addEventListener('storage', function(e) {
            if (e.key === 'cart') {
                loadCart();
            }
        });
    </script>
</body>
</html>