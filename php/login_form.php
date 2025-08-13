
<div class="login_regiter_form">
    <div class="wrapper">
        <span class="icon_close"><ion-icon name="close-outline"></ion-icon></span>
        <div class="form-box login">
            <h2>Đăng Nhập</h2>
            <form id="loginForm" action="./assets/php/login.php" method="post">
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