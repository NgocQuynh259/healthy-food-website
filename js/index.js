// Hiệu ứng xuất hiện
AOS.init({
    duration: 1500,
    offset: 100,
    once: true,
    easing: "ease-in-out",
});

window.addEventListener("scroll", function () {
    const navbar = document.querySelector(".navbar");
    if (window.scrollY > 10) {
        navbar.classList.add("scrolled");
    } else {
        navbar.classList.remove("scrolled");
    }
});

document.addEventListener("DOMContentLoaded", () => {
    const wrapper = document.querySelector(".wrapper");
    const loginLink = document.querySelector(".login_link");
    const registerLink = document.querySelector(".register_link");
    const btnClose = document.querySelector(".icon_close");
    const closeContainerForm = document.querySelector(".login_regiter_form");
    const homeLink = document.querySelector(".navbar_home");

    // 🌟 Luôn kiểm tra localStorage khi load lại trang
    const vaitroLocal = localStorage.getItem("vaitro");
    if (homeLink) {
        if (vaitroLocal === "1") {
            homeLink.innerText = "Admin";
            homeLink.setAttribute("href", "../php/admin.php");
        } else {
            homeLink.innerText = "Trang Chủ";
            homeLink.setAttribute("href", "../php/index.php");
        }
    }

    if (loginLink) {
        loginLink.addEventListener("click", () =>
            wrapper.classList.remove("active")
        );
    }
    if (registerLink) {
        registerLink.addEventListener("click", () =>
            wrapper.classList.add("active")
        );
    }

    document.addEventListener("click", function (event) {
        const btnPopup = event.target.closest(".navbar_log_in");
        if (btnPopup) {
            wrapper.classList.add("active-popup");
            if (closeContainerForm) closeContainerForm.style.display = "flex";
        }

        if (
            (!wrapper.contains(event.target) && !btnPopup) ||
            (btnClose && btnClose.contains(event.target))
        ) {
            if (wrapper.classList.contains("active-popup")) {
                wrapper.classList.remove("active-popup");
                wrapper.classList.remove("active");
                if (closeContainerForm)
                    closeContainerForm.style.display = "none";
            }
        }
    });

    const loginForm = document.querySelector("#loginForm");
    if (loginForm) {
        loginForm.addEventListener("submit", function (e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch("../php/login.php", {
                method: "POST",
                body: formData,
                credentials: "include",
            })
                .then((res) => res.json())
                .then((data) => {
                    if (data.status === "success") {
                        showSuccess(data.message);

                        // 🌟 Cập nhật nút Trang chủ cho admin
                        if (data.vaitro == 1) {
                            if (homeLink) {
                                homeLink.innerText = "Admin";
                                homeLink.setAttribute(
                                    "href",
                                    "../php/admin.php"
                                );
                            }
                            localStorage.setItem("vaitro", "1");
                            window.location.href = "../php/admin.php";
                        } else {
                            if (homeLink) {
                                homeLink.innerText = "Trang Chủ";
                                homeLink.setAttribute(
                                    "href",
                                    "../php/index.php"
                                );
                            }

                            const loginFormContainer = document.querySelector(
                                ".login_regiter_form"
                            );
                            if (loginFormContainer)
                                loginFormContainer.style.display = "none";
                            wrapper.classList.remove("active-popup");

                            const navbarBtn =
                                document.querySelector(".navbar_btn");
                            if (!navbarBtn) return;
                            const lastName = data.username
                                .trim()
                                .split(" ")
                                .pop();
                            navbarBtn.innerHTML = `<span class="welcome_user">👋 ${lastName}</span>
                                <a href="#!" class="btn navbar_log_out">Đăng Xuất</a>`;
                            setTimeout(addLogoutListener, 0);

                            localStorage.setItem("isLoggedIn", "true");
                            localStorage.setItem("username", data.username);
                            localStorage.setItem("vaitro", data.vaitro);

                            const btnPopup =
                                document.querySelector(".navbar_log_in");
                            if (btnPopup) btnPopup.style.display = "none";
                        }
                    } else {
                        showError(data.message);
                    }
                })
                .catch((err) => {
                    console.error("Lỗi fetch:", err);
                    showError("Đăng nhập thất bại do lỗi hệ thống.");
                });
        });
    }
    const registerForm = document.querySelector("#registerForm");
    if (registerForm) {
        registerForm.addEventListener("submit", function (e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch("../php/register.php", {
                method: "POST",
                body: formData,
            })
                .then((res) => res.json())
                .then((data) => {
                    console.log("Phản hồi đăng ký:", data);
                    if (data.status === "success") {
                        showSuccess(data.message);
                        wrapper.classList.remove("active");
                    } else {
                        showError(data.message);
                    }
                })
                .catch((err) => {
                    console.error("Lỗi đăng ký:", err);
                    showError("Đăng ký thất bại do lỗi hệ thống.");
                });
        });
    }

    function addLogoutListener() {
        const logoutBtn = document.querySelector(".navbar_log_out");
        if (!logoutBtn) return;
        logoutBtn.addEventListener("click", () => {
            fetch("../php/logout.php", {
                method: "GET",
                credentials: "include",
            })
                .then((response) => {
                    if (!response.ok) throw new Error("Lỗi khi gọi logout.php");
                    return response.json();
                })
                .then((data) => {
                    if (data.status === "success") {
                        localStorage.clear();
                        const btnPopup =
                            document.querySelector(".navbar_log_in");
                        if (btnPopup) btnPopup.style.display = "block";
                        window.location.href = "../php/index.php";
                    } else {
                        showError("Đăng xuất thất bại: " + data.message);
                    }
                })
                .catch((error) => {
                    console.error("Lỗi khi đăng xuất:", error);
                    showError("Đăng xuất thất bại do lỗi hệ thống.");
                });
        });
    }

    // ✅ Kiểm tra đăng nhập (session) từ server
    fetch("../php/login.php")
        .then((res) => res.json())
        .then((data) => {
            if (data.status === "success") {
                if (data.vaitro == 1) {
                    if (homeLink) {
                        homeLink.innerText = "Admin";
                        homeLink.setAttribute("href", "../php/admin.php");
                    }
                    localStorage.setItem("vaitro", "1");
                } else {
                    if (homeLink) {
                        homeLink.innerText = "Trang Chủ";
                        homeLink.setAttribute("href", "../php/index.php");
                    }
                    localStorage.setItem("vaitro", "0");
                }

                const navbarBtn = document.querySelector(".navbar_btn");
                if (!navbarBtn) return;
                const lastName = data.username.trim().split(" ").pop();
                navbarBtn.innerHTML = `<span class="welcome_user">👋 ${lastName}</span>
                    <a href="#!" class="btn navbar_log_out">Đăng Xuất</a>`;
                setTimeout(addLogoutListener, 0);

                const btnPopup = document.querySelector(".navbar_log_in");
                if (btnPopup) btnPopup.style.display = "none";
            } else {
                const btnPopup = document.querySelector(".navbar_log_in");
                if (btnPopup) btnPopup.style.display = "block";
            }
        })
        .catch((error) => {
            console.error("Lỗi khi kiểm tra đăng nhập:", error);
            const btnPopup = document.querySelector(".navbar_log_in");
            if (btnPopup) btnPopup.style.display = "block";
        });

    // Form tư vấn được xử lý bởi tuvan.js
});
