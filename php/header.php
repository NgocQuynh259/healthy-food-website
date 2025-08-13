<?php
session_start(); // Gọi đầu tiên
$page = basename($_SERVER['PHP_SELF']); // Lấy tên file hiện tại

// Tính tổng item trong giỏ hàng
$cart_count = 0;
if (!empty($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    $cart_count = array_sum($_SESSION['cart']);
}
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!-- CSS cho form tư vấn -->
<link rel="stylesheet" href="/CoSo/css/multiForm.css">

<!-- Custom Popup System -->
<link rel="stylesheet" href="/CoSo/css/popup.css">
<script src="/CoSo/js/popup.js"></script>

<body>
    <header class="navbar">
        <div class="container">
            <div class="container_nav">
                <div class="navbar_bg">
                    <div class="navbar_img">
                        <a href="index.php"><img src="../assets/img/avt/logo.png" alt="logo"></a>
                    </div>
                    <div class="navbar_items_btn">
                        <div class="navbar_item">
                            <ul class="items_list">
                                <li class="item"><a href="index.php" class="navbar_home"><span class="underline">T</span>rang Chủ</a></li>
                                <li class="item"><a href="menu.php"><span class="underline">T</span>hực Đơn</a></li>
                                <!-- <li class="item"><a href="blog.php"><span class="underline">B</span>log</a></li> -->
                                <li class="item"><a class="tuvan"><span class="underline">T</span>ư Vấn</a></li>
                                <li class="item"><a href="#" onclick="checkLoginForTienDo(event)" data-href="tiendo.php"><span class="underline">T</span>iến Độ</a></li>
                                <li class="item"><a href="congcu.php"><span class="underline">C</span>ông Cụ</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="navbar_btn" id="user-actions">
                        <?php if (isset($_SESSION['username'])): ?>
                            <?php
                            $nameParts = explode(" ", $_SESSION['username']);
                            $lastName = end($nameParts);
                            ?>
                            <span class="welcome_user">👋 <?= htmlspecialchars($lastName) ?></span>
                            <?php if ($page === 'index.php'): ?>
                                <a href="#!" class="btn navbar_log_out">Đăng Xuất</a>
                            <?php else: ?>
                                <a href="#!" class="btn cart_icon" id="openCartPopup">
                                    <i class="fas fa-shopping-cart"></i>
                                    <span id="cartCount" class="cart-count"><?php echo $cart_count; ?></span>
                                </a>
                            <?php endif; ?>
                        <?php else: ?>
                            <a href="#!" class="btn navbar_log_in">Đăng Nhập</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- Form đăng nhập/đăng ký -->
        <div class="login_regiter_form">
            <div class="wrapper">
                <div class="icon_close"><img src="../assets/img/avt/close.png" alt=""></div>
                <div class="form-box login">
                    <h2>Đăng Nhập</h2>
                    <form id="loginForm" method="post">
                        <div class="input_box">
                            <span class="icon"></span>
                            <input type="email" placeholder=" " name="email" required>
                            <label>Email</label>
                        </div>
                        <div class="input_box">
                            <span class="icon"></span>
                            <input type="password" placeholder=" " name="matkhau" required>
                            <label>Mật Khẩu</label>
                        </div>
                        <div class="remember-forgot">
                            <label><input type="checkbox"> Lưu thông tin</label>
                            <a href="#">Quên mật khẩu?</a>
                        </div>
                        <button type="submit" class="btn_login">Đăng Nhập</button>
                        <div class="login-register">
                            <p>Bạn chưa có tài khoản? <a href="#" class="register_link">Đăng Ký</a></p>
                        </div>
                    </form>
                </div>
                <div class="form-box register">
                    <h2>Đăng Ký</h2>
                    <form id="registerForm" method="post" autocomplete="off">
                        <div class="input_box">
                            <span class="icon"></span>
                            <input type="text" placeholder=" " name="tendangnhap" required>
                            <label>Tên đăng nhập</label>
                        </div>
                        <div class="input_box">
                            <span class="icon"></span>
                            <input type="email" placeholder=" " name="email" required>
                            <label>Email</label>
                        </div>
                        <div class="input_box">
                            <span class="icon"></span>
                            <input type="password" placeholder=" " name="matkhau" required>
                            <label>Mật khẩu</label>
                        </div>
                        <div class="remember-forgot">
                            <label><input type="checkbox" required> Tôi đồng ý với điều khoản và chính sách.</label>
                        </div>
                        <button type="submit" class="btn_register">Đăng Ký</button>
                        <div class="login-register">
                            <p>Bạn đã có tài khoản? <a href="#" class="login_link">Đăng Nhập</a></p>
                        </div>
                    </form>
                    <div id="toast" class="toast hidden">
                        <span id="toastMessage"></span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Popup checkout -->
    <div id="checkoutPopup" class="checkout-popup">
        <div class="checkout-container">
            <div class="left-column">
                <section class="products-section">
                    <h1>Sản Phẩm Của Bạn</h1>
                    <div id="cartItems"></div>
                </section>
                <section class="order-summary-section">
                    <h2>Tóm Tắt Đơn Hàng</h2>
                    <div class="summary-details" id="summaryDetails"></div>
                </section>
            </div>
            <div class="right-column">
                <span id="closeCheckoutBtn" class="close-btn">×</span>
                <section class="delivery-info-section">
                    <h3>Thông Tin Giao Hàng</h3>
                    <div class="form-group">
                        <label for="del-date">Ngày Giao Hàng</label>
                        <input type="date" id="del-date" required>
                    </div>
                    <div class="form-group">
                        <label for="del-time">Thời Gian Giao Hàng</label>
                        <select id="del-time" required>
                            <option value="">Chọn giờ giao hàng</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="address">Địa Chỉ</label>
                        <input type="text" id="address" placeholder="Nhập địa chỉ" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Số Điện Thoại</label>
                        <input type="tel" id="phone" placeholder="Nhập số điện thoại" required>
                    </div>
                </section>
                <section class="payment-methods">
                    <h3>Phương Thức Thanh Toán</h3>
                    <div class="pm-list">
                        <label class="pm selected">
                            <input type="radio" name="payment" value="momo" checked>
                            <i class="fas fa-money-bill-wave"></i>
                            <span>MOMO</span>
                        </label>
                        <label class="pm">
                            <input type="radio" name="payment" value="bank">
                            <i class="fas fa-university"></i>
                            <span>Ngân hàng</span>
                        </label>
                    </div>
                </section>
                <button class="btn-place-order" id="placeOrderBtn">Đặt Hàng Ngay</button>
            </div>

        </div>
    </div>
    <div class="toast" id="MessageCheckout"></div>
</body>



<style>
    .cart_icon {
        position: relative;
    }

    .cart-count {
        position: absolute;
        top: -6px;
        right: -6px;
        background: #e74c3c;
        color: #fff;
        font-size: .75rem;
        padding: 2px 6px;
        border-radius: 50%;
    }

    /* Overlay cho popup */
    .checkout-popup {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 999;
        justify-content: center;
        align-items: center;
        overflow-y: auto;
    }

    .popup-content {
        background: #fff;
        border-radius: 8px;
        width: 90%;
        max-width: 600px;
        margin: 20px auto;
        position: relative;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from {
            transform: translateY(-50px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .close-btn {
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 30px;
        color: #333;
        cursor: pointer;
        background: none;
        border: none;
    }

    .close-btn:hover {
        color: #dc3545;
    }

    :root {
        --primary: #28a745;
        --primary-80: #1e7e34;
        --primary-bg: rgba(40, 167, 69, 0.1);
        --bg: #f0f2f5;
        --white: #fff;
        --text: #333;
        --border: #ddd;
    }

    /* Main Container */
    .checkout-container {
        width: 1330px;
        display: flex;
        background: var(--white);
        border-radius: 16px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        flex-wrap: wrap;
        margin: auto;
    }

    .left-column {
        flex: 1.5;
        padding: 40px 30px;
        border-right: 1px solid #e0e0e0;
        display: flex;
        flex-direction: column;
        gap: 30px;
    }

    .right-column {
        flex: 1;
        padding: 40px 30px;
        display: flex;
        flex-direction: column;
        gap: 30px;
        position: relative;
    }

    /* Section Titles */
    .checkout-container h1,
    .checkout-container h2,
    .checkout-container h3 {
        color: #2c3e50;
        font-weight: 700;
        margin-bottom: 10px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e0e0e0;
    }

    .checkout-container h1 {
        font-size: 2.6rem;
    }

    .checkout-container h2 {
        font-size: 2.3rem;
    }

    .checkout-container h3 {
        font-size: 2.2rem;
        border-bottom: 1px solid #eee;
    }

    /* Cart Items */
    .cart-item {
        display: grid;
        grid-template-columns: 80px 1fr 90px 70px 40px;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px dashed #e9ecef;
        gap: 25px;
    }

    .cart-item:last-of-type {
        border-bottom: none;
        padding-bottom: 0;
    }

    .cart-item img {
        width: 80px;
        height: 70px;
        object-fit: cover;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .item-name h4 {
        font-size: 1.6rem;
        font-weight: 600;
        color: #34495e;
        word-break: break-word;
    }

    .quantity-control {
        display: flex;
        justify-content: center;
        align-items: center;
        border: 1px solid var(--border);
        border-radius: 6px;
        overflow: hidden;
    }

    .quantity-control button {
        width: 30px;
        height: 30px;
        border: none;
        background: #f8f9fa;
        cursor: pointer;
        font-size: 2.1rem;
        color: #495057;
        transition: background .2s;
    }

    .quantity-control button:hover {
        background: #e9ecef;
    }

    .quantity-control span {
        width: 35px;
        text-align: center;
        font-weight: 600;
        font-size: 1.6rem;
        color: #212529;
    }

    .item-price {
        font-size: 1.8rem;
        font-weight: 700;
        text-align: right;
    }

    /* Order Summary */
    .summary-details .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        font-size: 1.8rem;
        padding-bottom: 6px;
        border-bottom: 1px dashed #e9ecef;
    }

    .summary-details .summary-row:last-of-type {
        border-bottom: none;
    }

    .summary-details .summary-row.total {
        font-size: 2.2rem;
        font-weight: 700;
        color: var(--primary);
    }

    /* Form Groups (Delivery Info) */
    .form-group {
        margin-bottom: 18px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-size: 1.8rem;
        color: #555;
        font-weight: 500;
    }

    .form-group input {
        width: 100%;
        padding: 12px 15px;
        font-size: 1.6rem;
        border: 1px solid #ced4da;
        border-radius: 8px;
        transition: border-color .3s, box-shadow .3s;
        background: #fff;
        color: #2c3e50;
    }

    .form-group input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.25);
    }

    /* Payment Methods */
    .pm-list {
        display: flex;
        gap: 12px;
    }

    .pm {
        display: flex;
        justify-content: center;
        align-items: center;
        flex: 1;
        position: relative;
        padding: 14px 10px;
        border: 2px solid var(--border);
        border-radius: 8px;
        text-align: center;
        cursor: pointer;
        transition: all .2s;
        gap: 10px;
    }

    .pm input {
        display: none;
    }

    .pm i {
        display: block;
        font-size: 2.6rem;
        margin-bottom: 8px;
        color: #888;
        transition: color .2s;
    }

    .pm span {
        font-size: 1.6rem;
        color: #555;
    }

    .pm:hover {
        border-color: var(--primary);
    }

    .pm:hover i {
        color: var(--primary);
    }

    .pm.selected {
        border-color: var(--primary);
        background: var(--primary-bg);
    }

    .pm.selected i {
        color: var(--primary);
    }

    .pm.selected::after {
        content: "\f00c";
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        position: absolute;
        top: 8px;
        right: 8px;
        color: var(--primary);
        font-size: 1.9rem;
    }

    /* Place Order Button */
    .btn-place-order {
        width: 100%;
        padding: 16px 0;
        border: none;
        border-radius: 10px;
        font-size: 2rem;
        cursor: pointer;
        margin-top: 20px;
        background: linear-gradient(45deg, var(--primary), var(--primary-80));
        color: #fff;
        font-weight: 700;
        letter-spacing: .8px;
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        transition: all .3s;
    }

    .btn-place-order:hover {
        background: linear-gradient(45deg, var(--primary-80), #155724);
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
    }

    /* Success Message */
    .success-message {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
        border-radius: 10px;
        padding: 25px;
        margin-top: 25px;
        text-align: center;
        font-size: 2.15rem;
        font-weight: 500;
        display: none;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        line-height: 1.8;
    }

    .success-message strong {
        color: #0f3d1e;
    }

    .toast {
        position: fixed;
        height: 100px;
        width: 400px;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: #28a745;
        color: #fff;
        padding: 16px 24px;
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.25);
        z-index: 9999;
        display: none;
        font-size: 16px;
        text-align: center;
        animation: fadein 0.3s, fadeout 0.3s 2.7s;
    }

    .toast.show {
        display: block;
    }

    @keyframes fadein {
        from {
            opacity: 0;
            transform: translate(-50%, -60%);
        }

        to {
            opacity: 1;
            transform: translate(-50%, -50%);
        }
    }

    @keyframes fadeout {
        from {
            opacity: 1;
            transform: translate(-50%, -50%);
        }

        to {
            opacity: 0;
            transform: translate(-50%, -60%);
        }
    }


    .remove-item {
        background: none;
        border: none;
        font-size: 18px;
        color: #ff0000;
        cursor: pointer;
        padding: 0 5px;
    }

    .remove-item:hover {
        color: #cc0000;
    }

    /* Định dạng cho select */
    #del-time {
        width: 100%;
        padding: 12px 15px;
        font-size: 1.6rem;
        border: 1px solid #ced4da;
        border-radius: 8px;
        transition: border-color .3s, box-shadow .3s;
        background: #fff;
        color: #2c3e50;
        outline: none;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
        appearance: none;
        /* Xóa giao diện mặc định của select */
        background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="%23666" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>');
        /* Mũi tên tùy chỉnh */
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 12px;
    }

    /* Khi select được focus */
    #del-time:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.25);
    }


    /* Định dạng cho các option */
    #del-time option {
        padding: 0.5rem;
        font-size: 1.6rem;
        color: #343a40;
        background-color: #fff;
    }
</style>
<script src="../js/cart.js"></script>
<script>
// Hàm kiểm tra đăng nhập khi click vào Tiến Độ
function checkLoginForTienDo(event) {
    event.preventDefault();
    
    <?php if (!isset($_SESSION['username']) || !isset($_SESSION['makh'])): ?>
        // Nếu chưa đăng nhập, hiển thị popup
        showLoginAlert('Bạn cần đăng nhập để xem tiến độ sức khỏe của mình!');
    <?php else: ?>
        // Nếu đã đăng nhập, chuyển đến trang tiến độ
        window.location.href = 'tiendo.php';
    <?php endif; ?>
}

// Hàm hiển thị thông báo yêu cầu đăng nhập
function showLoginAlert(message) {
    const alertHtml = `
        <div id="loginAlert" style="
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 10000;
        ">
            <div style="
                background: white;
                padding: 40px;
                border-radius: 20px;
                text-align: center;
                max-width: 500px;
                margin: 20px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.3);
                position: relative;
            ">
                <i class="fas fa-chart-line" style="
                    font-size: 4rem;
                    color: #ff6b6b;
                    margin-bottom: 20px;
                    display: block;
                "></i>
                <h2 style="
                    color: #333;
                    margin-bottom: 15px;
                    font-size: 1.8rem;
                    font-weight: 600;
                ">Yêu cầu đăng nhập</h2>
                <p style="
                    color: #666;
                    margin-bottom: 25px;
                    font-size: 1.1rem;
                    line-height: 1.5;
                ">${message}</p>
                <div style="display: flex; gap: 15px; justify-content: center;">
                    <button class="navbar_log_in" onclick="closeLoginAlert()" style="
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        color: white;
                        padding: 12px 30px;
                        border: none;
                        border-radius: 25px;
                        font-size: 1.1rem;
                        cursor: pointer;
                        transition: transform 0.2s;
                    ">Đăng nhập ngay</button>
                    <button onclick="closeLoginAlert()" style="
                        background: #6c757d;
                        color: white;
                        padding: 12px 30px;
                        border: none;
                        border-radius: 25px;
                        font-size: 1.1rem;
                        cursor: pointer;
                        transition: transform 0.2s;
                    ">Đóng</button>
                </div>
            </div>
        </div>
    `;
    document.body.insertAdjacentHTML('beforeend', alertHtml);
}



function closeLoginAlert() {
    const alert = document.getElementById('loginAlert');
    if (alert) {
        alert.remove();
    }
}
</script>

<!-- Load JavaScript cho form tư vấn sau khi DOM sẵn sàng -->
<script src="/CoSo/js/tuvan.js"></script>

<?php
// Include form tư vấn (phiên bản mới với đầy đủ tính năng)
include 'tuvan_component.php';
?>