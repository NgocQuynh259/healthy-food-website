(function () {
    // Hàm khởi tạo và render giỏ hàng
    function initializeCart() {
        const openCartPopup = document.getElementById("openCartPopup");
        if (!openCartPopup) {
            console.error("Không tìm thấy phần tử #openCartPopup");
            return;
        }
        const cartItemsDiv = document.getElementById("cartItems");
        const summaryDiv = document.getElementById("summaryDetails");
        const checkoutPopup = document.getElementById("checkoutPopup");
        const closeCheckoutBtn = document.getElementById("closeCheckoutBtn");
        const cartCount = document.getElementById("cartCount");
        let subTotal = 0;

        // Hiển thị giỏ hàng trong popup
        function renderCart() {
            const cart = JSON.parse(localStorage.getItem("cart") || "[]"); // Lấy cart mới nhất
            subTotal = 0;
            cartItemsDiv.innerHTML = "";
            cart.forEach((item) => {
                const lineTotal = item.price * item.quantity;
                subTotal += lineTotal;
                cartItemsDiv.insertAdjacentHTML(
                    "beforeend",
                    `
                    <div class="cart-item" data-id="${item.id}">
                        <img src="${item.image}" alt="${item.name}">
                        <div class="item-name"><h4>${item.name}</h4></div>
                        <div class="quantity-control">
                            <button class="decrease">−</button>
                            <span>${item.quantity}</span>
                            <button class="increase">+</button>
                        </div>
                        <div class="item-price">${lineTotal.toLocaleString(
                            "vi-VN"
                        )}₫</div>
                        <button class="remove-item">x</button>
                    </div>
                    `
                );
            });

            // Hiển thị tổng tiền
            const shippingFee = 30000;
            const total = subTotal + shippingFee;
            summaryDiv.innerHTML = `
                <div class="summary-row"><span>Tổng Phụ</span><span>${subTotal.toLocaleString(
                    "vi-VN"
                )}₫</span></div>
                <div class="summary-row"><span>Phí Vận Chuyển</span><span>${shippingFee.toLocaleString(
                    "vi-VN"
                )}₫</span></div>
                <div class="summary-row total"><span>Tổng Cộng</span><span>${total.toLocaleString(
                    "vi-VN"
                )}₫</span></div>
            `;

            // Gắn sự kiện cho các nút trong giỏ hàng
            document.querySelectorAll(".cart-item").forEach((el) => {
                const id = el.dataset.id;
                const btnDec = el.querySelector(".decrease");
                const btnInc = el.querySelector(".increase");
                const qtySpan = el.querySelector("span");
                const btnRemove = el.querySelector(".remove-item");

                // Xóa sự kiện cũ để tránh trùng lặp
                btnDec.replaceWith(btnDec.cloneNode(true));
                btnInc.replaceWith(btnInc.cloneNode(true));
                btnRemove.replaceWith(btnRemove.cloneNode(true));

                // Gắn sự kiện mới
                el.querySelector(".decrease").addEventListener("click", () =>
                    updateQty(id, -1, qtySpan)
                );
                el.querySelector(".increase").addEventListener("click", () =>
                    updateQty(id, +1, qtySpan)
                );
                el.querySelector(".remove-item").addEventListener("click", () =>
                    removeItem(id)
                );
            });

            // Cập nhật số lượng trên nút giỏ hàng
            cartCount.textContent = cart.reduce(
                (sum, item) => sum + item.quantity,
                0
            );
        }

        // Hàm cập nhật số lượng
        function updateQty(id, delta, span) {
            const cart = JSON.parse(localStorage.getItem("cart") || "[]");
            const idx = cart.findIndex((i) => i.id === id);
            if (idx === -1) {
                
                return;
            }
            if (cart[idx].quantity + delta < 1) {
                cart.splice(idx, 1);
            } else {
                cart[idx].quantity += delta;
            }
            localStorage.setItem("cart", JSON.stringify(cart));
            renderCart();
            window.dispatchEvent(new CustomEvent("cartUpdated"));

            fetch("/CoSo/php/update_cart.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: new URLSearchParams({
                    id: id,
                    cart: JSON.stringify(cart),
                }),
            })
                .then((response) => {
                    if (!response.ok)
                        throw new Error(
                            "Lỗi mạng hoặc server: " + response.status
                        );
                    return response.json();
                })
                .then((data) => {
                    if (!data.success) {
                        showToast(
                            data.error || "Không thể cập nhật số lượng!",
                            "error"
                        );
                    }
                })
                .catch((err) => {
                    showToast(
                        "Có lỗi khi cập nhật số lượng: " + err.message,
                        "error"
                    );
                });
        }

        // Hàm xóa sản phẩm
        function removeItem(id) {
            confirmAsync("Bạn có chắc muốn xóa sản phẩm này?").then((result) => {
                if (result) {
                    const cart = JSON.parse(localStorage.getItem("cart") || "[]"); // Lấy cart mới nhất
                    const idx = cart.findIndex((i) => i.id === id);
                    if (idx === -1) {
                        
                        return;
                    }
                    cart.splice(idx, 1);
                localStorage.setItem("cart", JSON.stringify(cart));
                
                // Kiểm tra nếu giỏ hàng trống thì đóng popup
                if (cart.length === 0) {
                    const checkoutPopup = document.getElementById("checkoutPopup");
                    if (checkoutPopup) {
                        checkoutPopup.style.display = "none";
                    }
                    // Cập nhật badge giỏ hàng về 0
                    const cartCount = document.getElementById("cartCount");
                    if (cartCount) {
                        cartCount.textContent = "0";
                        cartCount.style.display = "none";
                    }
                    showToast("Giỏ hàng trống!", "success");
                    // Load lại trang sau 1 giây
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    initializeCart(); // Tái khởi tạo để cập nhật giao diện và sự kiện
                }

                fetch("/CoSo/php/remove_from_cart.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded",
                    },
                    body: new URLSearchParams({
                        id: id,
                        cart: JSON.stringify(cart),
                    }),
                })
                    .then((response) => {
                        if (!response.ok) {
                            throw new Error(
                                "Lỗi mạng hoặc server: " + response.status
                            );
                        }
                        return response.json();
                    })
                    .then((data) => {
                        if (!data.success) {
                            const errorMsg =
                                data.error ||
                                "Không thể xóa sản phẩm, thử lại sau!";
                            console.error("Failed to remove item:", errorMsg);
                            showToast(errorMsg, "error");
                        }
                    })
                    .catch((err) => {
                        console.error("Lỗi khi xóa:", err.message);
                        showToast(
                            "Có lỗi xảy ra khi xóa sản phẩm: " + err.message,
                            "error"
                        );
                    });
                }
            });
        }

        // Hàm tạo tùy chọn thời gian giao hàng
        function populateDeliveryTimes(selectedDate) {
            const delTimeSelect = document.getElementById("del-time");
            delTimeSelect.innerHTML =
                '<option value="">Chọn giờ giao hàng</option>';

            const now = new Date();
            const currentHour = now.getHours();
            const currentDate = now.toISOString().split("T")[0];

            const isToday = selectedDate === currentDate;

            for (let hour = 6; hour <= 18; hour++) {
                if (isToday && hour <= currentHour) {
                    continue;
                }
                const timeStr = `${hour.toString().padStart(2, "0")}:00`;
                delTimeSelect.insertAdjacentHTML(
                    "beforeend",
                    `<option value="${timeStr}">${timeStr}</option>`
                );
            }

            if (delTimeSelect.options.length === 1 && isToday) {
                delTimeSelect.disabled = true;
            } else {
                delTimeSelect.disabled = false;
            }
        }

        // Xử lý khi thay đổi ngày giao hàng
        const delDateInput = document.getElementById("del-date");
        if (delDateInput) {
            delDateInput.addEventListener("change", function () {
                const selectedDate = this.value;
                populateDeliveryTimes(selectedDate);
            });

            // Khởi tạo thời gian giao hàng
            const today = new Date().toISOString().split("T")[0];
            delDateInput.min = today;
            if (delDateInput.value === today || !delDateInput.value) {
                populateDeliveryTimes(today);
            }
        }

        // Xử lý hiển thị/ẩn popup
        openCartPopup.addEventListener("click", (e) => {
            e.preventDefault();
            const cart = JSON.parse(localStorage.getItem("cart") || "[]"); // Lấy cart mới nhất
            console.log("Nút giỏ hàng được nhấn, cart:", cart);
            if (cart.length === 0) {
                showToast("Giỏ hàng của bạn đang trống!", "error");
                return;
            }
            checkoutPopup.style.display = "flex";
            renderCart(); // Cập nhật giao diện khi mở popup
        });

        closeCheckoutBtn.addEventListener("click", () => {
            checkoutPopup.style.display = "none";
        });

        checkoutPopup.addEventListener("click", (e) => {
            if (e.target === checkoutPopup) {
                checkoutPopup.style.display = "none";
            }
        });

        document
            .querySelectorAll('.pm input[name="payment"]')
            .forEach((radio) => {
                radio.addEventListener("change", (e) => {
                    document.querySelectorAll(".pm").forEach((label) => {
                        label.classList.remove("selected");
                    });
                    e.target.closest(".pm").classList.add("selected");
                });
            });
        

        // Gọi renderCart ban đầu
        renderCart();
    }

    // Gọi khởi tạo khi trang tải
    initializeCart();
})();

// Hàm hiển thị toast
function showToast(msg, type = "success") {
    const toast = document.getElementById("MessageCheckout");
    if (toast) {
        toast.style.background = type === "error" ? "#dc3545" : "#28a745";
        toast.style.display = "block";
        toast.textContent = msg;
        setTimeout(() => {
            toast.style.display = "none";
        }, 5000);
    }
}
document.addEventListener("DOMContentLoaded", function () {
    const cartItemsDiv = document.getElementById("cartItems");
    const summaryDiv = document.getElementById("summaryDetails");
    const delDateInput = document.getElementById("del-date");
    const placeOrderBtn = document.getElementById("placeOrderBtn");

    function renderCart() {
        const cart = getCart();
        let subTotal = 0;
        cartItemsDiv.innerHTML = "";
        cart.forEach((item) => {
            const lineTotal = item.price * item.quantity;
            subTotal += lineTotal;
            cartItemsDiv.insertAdjacentHTML(
                "beforeend",
                `
                            <div class="cart-item" data-id="${item.id}">
                                <img src="${item.image}" alt="${item.name}">
                                <div class="item-name"><h4>${
                                    item.name
                                }</h4></div>
                                <div class="quantity-control">
                                    <button class="decrease">−</button>
                                    <span>${item.quantity}</span>
                                    <button class="increase">+</button>
                                </div>
                                <div class="item-price">${lineTotal.toLocaleString(
                                    "vi-VN"
                                )}₫</div>
                                <button class="remove-item">x</button>
                            </div>
                            `
            );
        });

        const shippingFee = 30000;
        const total = subTotal + shippingFee;
        summaryDiv.innerHTML = `
                        <div class="summary-row"><span>Tổng Phụ</span><span>${subTotal.toLocaleString(
                            "vi-VN"
                        )}₫</span></div>
                        <div class="summary-row"><span>Phí Vận Chuyển</span><span>${shippingFee.toLocaleString(
                            "vi-VN"
                        )}₫</span></div>
                        <div class="summary-row total"><span>Tổng Cộng</span><span>${total.toLocaleString(
                            "vi-VN"
                        )}₫</span></div>
                    `;

        // Gắn sự kiện cho các nút
        document.querySelectorAll(".cart-item").forEach((el) => {
            const id = el.dataset.id;
            const btnDec = el.querySelector(".decrease");
            const btnInc = el.querySelector(".increase");
            const qtySpan = el.querySelector("span");
            const btnRemove = el.querySelector(".remove-item");

            btnDec.replaceWith(btnDec.cloneNode(true));
            btnInc.replaceWith(btnInc.cloneNode(true));
            btnRemove.replaceWith(btnRemove.cloneNode(true));

            el.querySelector(".decrease").addEventListener("click", () =>
                updateQty(id, -1, qtySpan)
            );
            el.querySelector(".increase").addEventListener("click", () =>
                updateQty(id, 1, qtySpan)
            );
            el.querySelector(".remove-item").addEventListener("click", () =>
                removeItem(id)
            );
        });

        // Gọi lại populateDeliveryTimes để duy trì trạng thái thời gian
        if (delDateInput && delDateInput.value) {
            populateDeliveryTimes(delDateInput.value);
        }
    }

    function getCart() {
        try {
            return JSON.parse(localStorage.getItem("cart") || "[]");
        } catch (e) {
            console.error("Lỗi parse localStorage:", e);
            return [];
        }
    }

    function updateQty(id, delta, span) {
        const cart = getCart();
        const idx = cart.findIndex((i) => i.id === id);
        if (idx === -1) {
            return;
        }
        if (cart[idx].quantity + delta < 1) {
            cart.splice(idx, 1);
        } else {
            cart[idx].quantity += delta;
        }
        localStorage.setItem("cart", JSON.stringify(cart));
        renderCart();
        window.dispatchEvent(new CustomEvent("cartUpdated"));

        fetch("/CoSo/php/update_cart.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: new URLSearchParams({
                id: id,
                cart: JSON.stringify(cart),
            }),
        })
            .then((response) => {
                if (!response.ok)
                    throw new Error("Lỗi mạng hoặc server: " + response.status);
                return response.json();
            })
            .then((data) => {
                if (!data.success) {
                    const errorMsg =
                        data.error ||
                        "Không thể cập nhật số lượng, thử lại sau!";
                    console.error("Failed to update quantity:", errorMsg);
                    showToast(errorMsg, "error");
                }
            })
            .catch((err) => {
                console.error("Lỗi khi cập nhật:", err.message);
                showToast(
                    "Có lỗi khi cập nhật số lượng: " + err.message,
                    "error"
                );
            });
    }

    function removeItem(id) {
        confirmAsync("Bạn có chắc muốn xóa sản phẩm này?").then((result) => {
            if (result) {
                const cart = getCart();
                const idx = cart.findIndex((i) => i.id === id);
                if (idx === -1) {
                    return;
                }
                cart.splice(idx, 1);
            localStorage.setItem("cart", JSON.stringify(cart));
            
            // Kiểm tra nếu giỏ hàng trống thì đóng popup
            if (cart.length === 0) {
                const checkoutPopup = document.getElementById("checkoutPopup");
                if (checkoutPopup) {
                    checkoutPopup.style.display = "none";
                }
                // Cập nhật badge giỏ hàng về 0
                const cartCount = document.getElementById("cartCount");
                if (cartCount) {
                    cartCount.textContent = "0";
                    cartCount.style.display = "none";
                }
                showToast("Giỏ hàng trống!", "success");
                // Load lại trang sau 1 giây
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                renderCart();
            }
            
            window.dispatchEvent(new CustomEvent("cartUpdated"));

            fetch("/CoSo/php/remove_from_cart.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: new URLSearchParams({
                    id: id,
                    cart: JSON.stringify(cart),
                }),
            })
                .then((response) => {
                    if (!response.ok)
                        throw new Error(
                            "Lỗi mạng hoặc server: " + response.status
                        );
                    return response.json();
                })
                .then((data) => {
                    if (!data.success) {
                        const errorMsg =
                            data.error ||
                            "Không thể xóa sản phẩm, thử lại sau!";
                        console.error("Failed to remove item:", errorMsg);
                        showToast(errorMsg, "error");
                    }
                })
                .catch((err) => {
                    console.error("Lỗi khi xóa:", err.message);
                    showToast(
                        "Có lỗi xảy ra khi xóa sản phẩm: " + err.message,
                        "error"
                    );
                });
            }
        });
    }

    function populateDeliveryTimes(selectedDate) {
        const delTimeSelect = document.getElementById("del-time");
        if (!delTimeSelect) {
            console.error("Không tìm thấy select giờ giao hàng!");
            return;
        }

        // Chuẩn hóa ngày được chọn về định dạng yyyy-mm-dd
        const dateObj = new Date(selectedDate);
        if (isNaN(dateObj.getTime())) {
            console.error("❌ Ngày không hợp lệ:", selectedDate);
            return;
        }

        const yyyy = dateObj.getFullYear();
        const mm = String(dateObj.getMonth() + 1).padStart(2, "0");
        const dd = String(dateObj.getDate()).padStart(2, "0");
        const normalizedSelectedDate = `${yyyy}-${mm}-${dd}`;

        const now = new Date();
        const currentHour = now.getHours();
        const currentDate = now.toISOString().split("T")[0];
        const isToday = normalizedSelectedDate === currentDate;

        delTimeSelect.innerHTML =
            '<option value="">Chọn giờ giao hàng</option>';
        let addedCount = 0;

        if (isToday && currentHour >= 18) {
            // showToast("Đã quá thời gian giao hàng trong hôm nay!", "error");
            return;
        }

        for (let hour = 6; hour <= 18; hour++) {
            if (isToday && hour <= currentHour) continue;

            const timeStr = `${hour.toString().padStart(2, "0")}:00`;
            delTimeSelect.insertAdjacentHTML(
                "beforeend",
                `<option value="${timeStr}">${timeStr}</option>`
            );
            console.log("✅ Đã thêm giờ:", timeStr);
            addedCount++;
        }

    }

    if (delDateInput) {
        const today = new Date().toISOString().split("T")[0];
        delDateInput.min = today;
        if (!delDateInput.value) {
            delDateInput.value = today;
            console.log("📅 Đã gán ngày mặc định:", today);
        } else {
            console.log("📅 Ngày hiện tại từ input:", delDateInput.value);
        }
        setTimeout(() => {
            console.log(
                "⏰ Gọi populateDeliveryTimes với:",
                delDateInput.value
            );
            populateDeliveryTimes(delDateInput.value);
        }, 200);

        delDateInput.addEventListener("change", function () {
            console.log("📅 Ngày được chọn thay đổi:", this.value);
            populateDeliveryTimes(this.value);
        });
    }

    console.log("placeOrderBtn:", placeOrderBtn); // Kiểm tra xem phần tử có được tìm thấy không
    if (!placeOrderBtn) {
        console.error("Không tìm thấy nút placeOrderBtn!");
        return;
    }
    placeOrderBtn.addEventListener("click", function () {
        console.log("Nút Đặt Hàng được nhấn");
        const cart = getCart();
        if (!cart.length) {
            showToast("Giỏ hàng của bạn đang trống!", "error");
            return;
        }

        const deliveryInfo = {
            address: document.getElementById("address").value.trim(),
            phone: document.getElementById("phone").value.trim(),
            date: document.getElementById("del-date").value,
            time: document.getElementById("del-time").value,
            payment: document.querySelector('input[name="payment"]:checked')
                ?.value,
        };

        if (
            !deliveryInfo.address ||
            !deliveryInfo.phone ||
            !deliveryInfo.date ||
            !deliveryInfo.time ||
            !deliveryInfo.payment
        ) {
            showToast("Vui lòng điền đầy đủ thông tin giao hàng!", "error");
            return;
        }

        if (!/^\d{10}$/.test(deliveryInfo.phone.replace(/[^0-9]/g, ""))) {
            showToast("Số điện thoại không hợp lệ!", "error");
            return;
        }

        const now = new Date();
        const currentDate = now.toISOString().split("T")[0];
        const [selectedHour] = deliveryInfo.time.split(":").map(Number);
        const isToday = deliveryInfo.date === currentDate;

        if (isToday && selectedHour <= now.getHours()) {
            showToast(
                "Thời gian giao hàng không hợp lệ! Vui lòng chọn khung giờ sau thời gian hiện tại.",
                "error"
            );
            return;
        }

        const orderCart = cart.map((item) => ({
            id: item.id,
            quantity: item.quantity,
        }));

        fetch("/CoSo/php/place_order.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: `cart=${encodeURIComponent(
                JSON.stringify(orderCart)
            )}&delivery=${encodeURIComponent(JSON.stringify(deliveryInfo))}`,
        })
            .then((response) => {
                if (!response.ok)
                    throw new Error(`Lỗi HTTP! Trạng thái: ${response.status}`);
                return response.json();
            })
            .then((data) => {
                console.log("Response:", data);
                if (data.success) {
                    console.log("🎉 Thanh toán thành công! Trang hiện tại:", window.location.href);
                    showToast("Đặt hàng thành công!");
                    localStorage.removeItem("cart");
                    document.getElementById("cartCount").textContent = "0";
                    document.getElementById("checkoutPopup").style.display = "none";
                    
                    // Thông báo cập nhật combo progress
                    if (typeof window.notifyComboUpdate === 'function') {
                        window.notifyComboUpdate();
                    }
                    
                    // Hiển thị popup hóa đơn
                    if (data.hoadon_id) {
                        console.log("Calling showInvoicePopup with ID:", data.hoadon_id);
                        setTimeout(() => {
                            showInvoicePopup(data.hoadon_id);
                        }, 1000);
                    }
                    // Không chuyển trang, chỉ ở lại trang hiện tại
                    console.log("✅ Đã xử lý xong, ở lại trang:", window.location.href);
                } else {
                    showToast(
                        (data.msg || "Có lỗi!") +
                            (data.errors ? "\n" + data.errors.join("\n") : ""),
                        "error"
                    );
                }
            })
            .catch((err) => {
                showToast("Không thể kết nối server!\n" + err.message, "error");
                console.error(err);
            });
    });

    // Function hiển thị popup hóa đơn
    function showInvoicePopup(hoadonId) {
        console.log("showInvoicePopup called with ID:", hoadonId);
        // Lấy dữ liệu hóa đơn từ server
        fetch(`/CoSo/php/get_invoice_data.php?id=${hoadonId}`)
            .then(response => {
                console.log("Response status:", response.status);
                return response.json();
            })
            .then(data => {
                console.log("Invoice data received:", data);
                if (data.success) {
                    createInvoicePopup(data);
                } else {
                    showToast("Không thể tải thông tin hóa đơn: " + data.message, "error");
                }
            })
            .catch(error => {
                console.error('Error loading invoice:', error);
                showToast("Lỗi khi tải hóa đơn: " + error.message, "error");
            });
    }

    // Function tạo popup hóa đơn đơn giản
    function createInvoicePopup(data) {
        const hoadon = data.hoadon;
        const items = data.items;
        
        // Tạo overlay
        const overlay = document.createElement('div');
        overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
        `;
        
        // Tạo popup content
        const popup = document.createElement('div');
        popup.className = 'invoice-popup-content';
        popup.style.cssText = `
            background: white;
            border-radius: 15px;
            padding: 30px;
            max-width: 900px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            position: relative;
        `;
        
        // Nội dung hóa đơn đơn giản
        popup.innerHTML = `
            <style>
                @media print {
                    body * { visibility: hidden; }
                    .invoice-popup-content, .invoice-popup-content * { visibility: visible; }
                    .invoice-popup-content {
                        position: absolute !important;
                        left: 0 !important;
                        top: 0 !important;
                        width: 100% !important;
                        height: auto !important;
                        max-width: none !important;
                        max-height: none !important;
                        overflow: visible !important;
                        background: white !important;
                        box-shadow: none !important;
                        border-radius: 0 !important;
                        padding: 20px !important;
                        margin: 0 !important;
                    }
                    .close-btn, .bottom-buttons { display: none !important; }
                    @page { size: A4; margin: 10mm; }
                }
                
                .invoice-header {
                    text-align: center;
                    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
                    color: white;
                    padding: 20px;
                    margin: -30px -30px 20px -30px;
                    border-radius: 15px 15px 0 0;
                }
                .invoice-title {
                    font-size: 24px;
                    font-weight: bold;
                    margin: 0;
                }
                .invoice-subtitle {
                    font-size: 14px;
                    margin: 5px 0;
                }
                .info-grid {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: 15px;
                    margin-bottom: 20px;
                }
                .info-section {
                    background: #f8f9fa;
                    padding: 15px;
                    border-radius: 8px;
                    margin-bottom: 15px;
                    border-left: 4px solid #28a745;
                }
                .info-section h3 {
                    color: #28a745;
                    font-size: 16px;
                    margin-bottom: 10px;
                    font-weight: bold;
                }
                .info-row {
                    margin-bottom: 8px;
                    font-size: 14px;
                }
                .label {
                    font-weight: bold;
                    color: #2d3748;
                    display: inline-block;
                    min-width: 100px;
                }
                .items-table {
                    width: 100%;
                    border-collapse: collapse;
                    margin: 15px 0;
                    font-size: 14px;
                    background: white;
                    border-radius: 8px;
                    overflow: hidden;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                }
                .items-table th, .items-table td {
                    padding: 10px;
                    
                }
                .items-table th {
                    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
                    color: white;
                    font-weight: bold;
                }
                .items-table tr:nth-child(even) {
                    background-color: #f8f9fa;
                }
                .text-center { text-align: center; }
                .text-right { text-align: right; }
                .total-row {
                    background:rgb(25, 105, 44)!important;
                    color: #155724 !important;
                    font-weight: bold;
                    font-size: 16px;
                }
                .close-btn {
                    position: absolute;
                    top: 15px;
                    right: 20px;
                    background: #dc3545;
                    color: white;
                    border: none;
                    width: 30px;
                    height: 30px;
                    border-radius: 50%;
                    font-size: 16px;
                    cursor: pointer;
                    line-height: 1;
                }
                .close-btn:hover {
                    background: #c82333;
                }
                .bottom-buttons {
                    text-align: center;
                    margin-top: 20px;
                }
                .btn {
                    padding: 10px 20px;
                    margin: 0 5px;
                    border: none;
                    border-radius: 5px;
                    font-size: 14px;
                    cursor: pointer;
                    font-weight: bold;
                }
                .btn-primary {
                    background: #28a745;
                    color: white;
                }
                .btn-primary:hover {
                    background: #218838;
                }
                .btn-secondary {
                    background: #6c757d;
                    color: white;
                }
                .btn-secondary:hover {
                    background: #545b62;
                }
            </style>
            
            <button class="close-btn" onclick="closeInvoicePopup()" title="Đóng">&times;</button>
            
            <div class="invoice-header">
                <h1 class="invoice-title"> Hóa đơn thanh toán TQFood🍽️ </h1>
                <p class="invoice-subtitle"></p>
                <p class="invoice-subtitle">Số hóa đơn: #${hoadon.id}</p>
                <p class="invoice-subtitle">Ngày tạo: ${new Date(hoadon.created_at).toLocaleString('vi-VN')}</p>
            </div>
            
            <div class="info-grid">
                <div class="info-section">
                    <h3>👤 Thông tin khách hàng</h3>
                    <div class="info-row">
                        <span class="label">Tên:</span> ${hoadon.customer.name}
                    </div>
                    <div class="info-row">
                        <span class="label">Số điện thoại:</span> ${hoadon.customer.phone}
                    </div>
                    <div class="info-row">
                        <span class="label">Địa chỉ:</span> ${hoadon.customer.address}
                    </div>
                </div>
                
                <div class="info-section">
                    <h3>🚚 Thông tin giao hàng</h3>
                    <div class="info-row">
                        <span class="label">Ngày giao:</span> ${new Date(hoadon.ship_date).toLocaleDateString('vi-VN')}
                    </div>
                    <div class="info-row">
                        <span class="label">Thời gian:</span> ${hoadon.ship_time}
                    </div>
                    <div class="info-row">
                        <span class="label">Thanh toán:</span> ${hoadon.payment_method.toUpperCase()}
                    </div>
                    <div class="info-row">
                        <span class="label">Trạng thái:</span> <span style="color: #28a745;">✓ ${hoadon.status}</span>
                    </div>
                </div>
            </div>
            
            <div class="info-section">
                <h3>📋 Chi tiết đơn hàng</h3>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Tên sản phẩm</th>
                            <th class="text-center">Số lượng</th>
                            <th class="text-right">Đơn giá</th>
                            <th class="text-right">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${items.map((item, index) => `
                            <tr>
                                <td class="text-center">${index + 1}</td>
                                <td>${item.name}</td>
                                <td class="text-center">${item.quantity}</td>
                                <td class="text-right">${new Intl.NumberFormat('vi-VN').format(item.price)} đ</td>
                                <td class="text-right">${new Intl.NumberFormat('vi-VN').format(item.total_price)} đ</td>
                            </tr>
                        `).join('')}
                        <tr style="border-top: 2px solid #28a745;">
                            <td colspan="4" class="text-right" style="font-weight: bold; padding-top: 15px;">Tổng phụ:</td>
                            <td class="text-right" style="font-weight: bold; padding-top: 15px;">${new Intl.NumberFormat('vi-VN').format(hoadon.subtotal)} đ</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-right" style="font-weight: bold;">Phí vận chuyển:</td>
                            <td class="text-right" style="font-weight: bold; color: ${hoadon.shipping_fee > 0 ? '#dc3545' : '#28a745'};">
                                ${hoadon.shipping_fee > 0 ? new Intl.NumberFormat('vi-VN').format(hoadon.shipping_fee) + ' đ' : 'Miễn phí'}
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr class="total-row">
                            <td colspan="4" class="text-right"><strong>TỔNG CỘNG:</strong></td>
                            <td class="text-right"><strong>${new Intl.NumberFormat('vi-VN').format(hoadon.total_amount)} đ</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <div class="bottom-buttons">
                <button class="btn btn-primary" onclick="window.print()">
                    🖨️ In hóa đơn
                </button>
                <button class="btn btn-secondary" onclick="closeInvoicePopup()">
                    ❌ Đóng
                </button>
            </div>
            
            <div style="text-align: center; margin-top: 15px; font-size: 12px; color: #666;">
                Sự hài lòng của quý khác là ưu tiên hàng đầu! 
            </div>
        `;
        
        overlay.appendChild(popup);
        document.body.appendChild(overlay);
        
        // Thêm function đóng popup vào window
        window.closeInvoicePopup = function() {
            console.log("🔄 Đóng popup hóa đơn, ở lại trang:", window.location.href);
            document.body.removeChild(overlay);
            // Không chuyển trang, chỉ đóng popup và ở lại trang hiện tại
            console.log("✅ Đã đóng popup hóa đơn, vẫn ở trang:", window.location.href);
        };
        
        // Đóng popup khi click vào overlay
        overlay.addEventListener('click', function(e) {
            if (e.target === overlay) {
                window.closeInvoicePopup();
            }
        });
        
        // Đóng popup khi nhấn Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                window.closeInvoicePopup();
            }
        });
    }

    renderCart();
    console.log("🛒 Giỏ hàng đã được render.");
    window.addEventListener("cartUpdated", renderCart);
});
