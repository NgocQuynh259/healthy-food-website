<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/CoSo/css/reset.css">
    <link rel="stylesheet" href="/CoSo/css/general.css">
    <link rel="stylesheet" href="/CoSo/css/index.css">
    <link rel="stylesheet" href="/CoSo/css/menu.css">
    <!-- <link rel="stylesheet" href="/CoSo/css/multiForm.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Thực đơn</title>
</head>

<body>
    <?php
    // Chỉ include header, không kiểm tra đăng nhập ở đây
    include 'header.php';
    ?>

    <div class="background">
    </div>
    <!-- Intro Menu -->
    <div class="intro_menu">
        <h1>Thực Đơn Dinh Dưỡng Từ Chuyên Gia Hàng Đầu Việt Nam</h1>
        <p>Hơn 50 món ăn mỗi ngày với dinh dưỡng mà bạn cần và hương vị mà bạn yêu thích.</p>

    </div>

    <!-- Tag Menu -->
    <div class="container_menu">
        <div class="container">
            <div class="menu_card">
                <div class="menu">
                    <nav class="sidebar">
                        <h2>Thực Đơn</h2>
                        <ul>
                            <li class="menu_item" data-category="balance">
                                <img src="/CoSo/assets/img/avt/balance.png" alt="">
                                <a href="#!" class="active">Ăn Cân Bằng</a>
                            </li>
                            <li class="menu_item" data-category="calorie">
                                <img src="/CoSo/assets/img/avt/calorie.png" alt="">
                                <a href="#!">Giảm Calo</a>
                            </li>
                            <li class="menu_item" data-category="diabetic">
                                <img src="/CoSo/assets/img/avt/diabetes.png" alt="">
                                <a href="#!">Kiểm Soát Đường</a>
                            </li>
                            <li class="menu_item" data-category="gluten">
                                <img src="/CoSo/assets/img/avt/flutenfree.png" alt="">
                                <a href="#!">Không Gluten</a>
                            </li>
                            <li class="menu_item" data-category="heart">
                                <img src="/CoSo/assets/img/avt/heart.png" alt="">
                                <a href="#!">Tốt Cho Tim</a>
                            </li>
                            <li class="menu_item" data-category="keto">
                                <img src="/CoSo/assets/img/avt/keto.png" alt="">
                                <a href="#!">Chế độ Keto</a>
                            </li>
                            <li class="menu_item" data-category="protein">
                                <img src="/CoSo/assets/img/avt/protein.png" alt="">
                                <a href="#!">Giàu Protein</a>
                            </li>
                            <li class="menu_item" data-category="vegan">
                                <img src="/CoSo/assets/img/avt/vegan.png" alt="">
                                <a href="#!">Thuần chay</a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <div class="menu_balance" id="product_list">
                    <!-- Dữ liệu sẽ được load động vào đây -->
                </div>
            </div>
        </div>
    </div>

    <!-- Popup hiển thị thông tin món ăn -->
    <div class="popup_sanpham">
        <div class="popup-container">
            <!-- Nút đóng popup -->
            <button class="close-popup" onclick="$('.popup_sanpham').fadeOut()">×</button>
            <div class="popup-header">
                <h2 class="dish-title"></h2>
            </div>
            <div class="popup-body">
                <div class="column-left">
                    <img class="dish-image" src="" alt="Món Gà Tuscan">

                    <div id="dynamic-tags-container" class="tags-wrapper">
                    </div>
                    <p class="dish-price">Giá: 69.000đ</p>
                    <button class="cta-button">THÊM VÀO GIỎ HÀNG</button>
                </div>
                <div class="column-right">
                    <div class="info-section">
                        <h3>Dinh Dưỡng <small>(mỗi khẩu phần)</small></h3>
                        <ul class="nutrition-list">
                            <li><span>Calo</span> <strong>360kcal</strong></li>
                            <li><span>Đạm</span> <strong>39g</strong></li>
                            <li><span>Tổng Béo</span> <strong>11g</strong></li>
                            <li><span>Carbs</span> <strong>26g</strong></li>
                            <li><span>Chất xơ</span> <strong>5g</strong></li>
                            <li><span>Đường</span> <strong>8g</strong></li>
                        </ul>
                    </div>

                    <div class="info-section">
                        <h3>Về Món Ăn</h3>
                        <p class="about-dish-text"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer -->
    <?php include 'footer.php' ?>

    <!-- Thêm jQuery và JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Load sản phẩm mặc định (balance) khi trang tải
            loadProducts('balance');

            // Xử lý khi click vào danh mục
            $('.menu_item').click(function() {
                $('.menu_item a').removeClass('active');
                $(this).find('a').addClass('active');
                var category = $(this).data('category');
                loadProducts(category);
            });

            // Hàm load sản phẩm
            function loadProducts(category) {
                $.ajax({
                    url: '/CoSo/php/get_products.php',
                    type: 'GET',
                    data: {
                        category,
                        type: 'menu'
                    },
                    dataType: 'json',
                    success: function(products) {
                        let html = '';
                        if (products.length) {
                            products.forEach(function(product) {
                                let imagePath = product.Hinhanh;
                                html += `
                        <div class="card" data-id="${product.Masp}">
                            <img src="${imagePath}" alt="${product.Tensp}" class="food-img">
                            <div class="text-content">
                                <h3>${product.Tensp}</h3>
                                <div class="nutrition-icons">
                                    <span><img src="/CoSo/assets/img/avt/fire.png" alt="">${product.Calories} kcal</span>
                                    <span><img src="/CoSo/assets/img/avt/sugar-cube.png" alt="">${product.Sugar}g</span>
                                    <span><img src="/CoSo/assets/img/avt/muscle.png" alt="">${product.Protein}g</span>
                                    <span><img src="/CoSo/assets/img/avt/leaf.png" alt="">${product.Fiber}g</span>
                                </div>
                            </div>
                        </div>`;
                            });
                        } else {
                            html = '<p>Không có sản phẩm trong danh mục này!</p>';
                        }
                        $('#product_list').html(html);
                        $('.card').on('click', function() {
                            loadProductDetails($(this).data('id'));
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error (loadProducts): ", status, error);
                        $('#product_list').html('<p>Lỗi khi tải sản phẩm!</p>');
                    }
                });
            }

            function loadProductDetails(productId) {
                $.ajax({
                    url: '/CoSo/php/get_product_detail.php',
                    type: 'GET',
                    data: {
                        id: productId
                    },
                    dataType: 'json',
                    success: function(data) {
                        $('.dish-title').text(data.Tensp);
                        let imagePath = data.Hinhanh;
                        $('.dish-image').attr('src', imagePath);
                        $('.dish-price').text(formatCurrency(data.Giaban));
                        $('.nutrition-list').html(`
                    <li><span>Calo</span> <strong>${data.Calories}kcal</strong></li>
                    <li><span>Đạm</span> <strong>${data.Protein}g</strong></li>
                    <li><span>Tổng Béo</span> <strong>${data.Fat}g</strong></li>
                    <li><span>Carbs</span> <strong>${data.Carbs}g</strong></li>
                    <li><span>Chất xơ</span> <strong>${data.Fiber}g</strong></li>
                    <li><span>Đường</span> <strong>${data.Sugar}g</strong></li>
                `);
                        $('.about-dish-text').text(data.Mota);
                        $('#dynamic-tags-container').html(taoNhanDinhDuong(data));

                        $('.cta-button').off('click').on('click', function() {
                            // Kiểm tra đăng nhập phía client trước
                            <?php if (!isset($_SESSION['username']) || !isset($_SESSION['makh'])): ?>
                                // Nếu chưa đăng nhập, hiển thị popup yêu cầu đăng nhập
                                showLoginAlert('Bạn cần đăng nhập để thêm sản phẩm vào giỏ hàng!');
                                return; // Dừng thực hiện tiếp
                            <?php endif; ?>

                            // Nếu đã đăng nhập, thực hiện thêm vào giỏ hàng
                            const cart = JSON.parse(localStorage.getItem('cart') || '[]');
                            const pid = productId.toString();
                            const idx = cart.findIndex(i => i.id === pid);
                            if (idx > -1) {
                                cart[idx].quantity += 1;
                            } else {
                                cart.push({
                                    id: pid,
                                    name: data.Tensp,
                                    price: Number(data.Giaban),
                                    image: data.Hinhanh,
                                    quantity: 1,
                                    combo_details: null
                                });
                            }
                            localStorage.setItem('cart', JSON.stringify(cart));

                            // Gửi lên server để xác thực lần nữa
                            $.post('/CoSo/php/add_to_cart.php', {
                                id: pid,
                                cart: JSON.stringify(cart)
                            }).done(function(res) {
                                if (res.success && res.count !== undefined) {
                                    updateCartBadge();
                                    $('.popup_sanpham').fadeOut();
                                    // Kích hoạt sự kiện tùy chỉnh để thông báo cho cart.js
                                    const event = new CustomEvent('cartUpdated');
                                    window.dispatchEvent(event);

                                    // Hiển thị thông báo thành công
                                    const toast = document.createElement('div');
                                    toast.className = 'toast show';
                                    toast.style.background = '#28a745';
                                    toast.innerHTML = 'Đã thêm "' + data.Tensp + '" vào giỏ hàng!';
                                    document.body.appendChild(toast);
                                    setTimeout(() => toast.remove(), 3000);
                                } else {
                                    // Nếu server trả về lỗi (như chưa đăng nhập), hiển thị popup
                                    if (res.error && res.error.includes('đăng nhập')) {
                                        showLoginAlert('Bạn cần đăng nhập để thêm sản phẩm vào giỏ hàng!');
                                        // Xóa khỏi localStorage vì server reject
                                        const updatedCart = JSON.parse(localStorage.getItem('cart') || '[]');
                                        const index = updatedCart.findIndex(i => i.id === pid);
                                        if (index > -1) {
                                            if (updatedCart[index].quantity > 1) {
                                                updatedCart[index].quantity -= 1;
                                            } else {
                                                updatedCart.splice(index, 1);
                                            }
                                            localStorage.setItem('cart', JSON.stringify(updatedCart));
                                        }
                                    } else {
                                        showError('Lỗi thêm vào giỏ hàng: ' + (res.error || 'Không xác định'));
                                    }
                                }
                            }).fail(function() {
                                showError('Không thể kết nối server');
                                // Xóa khỏi localStorage vì không thể kết nối
                                const updatedCart = JSON.parse(localStorage.getItem('cart') || '[]');
                                const index = updatedCart.findIndex(i => i.id === pid);
                                if (index > -1) {
                                    if (updatedCart[index].quantity > 1) {
                                        updatedCart[index].quantity -= 1;
                                    } else {
                                        updatedCart.splice(index, 1);
                                    }
                                    localStorage.setItem('cart', JSON.stringify(updatedCart));
                                }
                            });
                        });

                        $('.popup_sanpham').fadeIn();
                    },
                    error: function(xhr, status, error) {
                        console.error('Lỗi khi tải chi tiết sản phẩm:', status, error);
                        showError('Lỗi khi tải chi tiết sản phẩm!');
                    }
                });
            }

            function formatCurrency(amount) {
                return 'Giá: ' + Number(amount).toLocaleString('vi-VN') + 'đ';
            }

            function taoNhanDinhDuong(monAn) {
                let tagsHTML = '';
                if (monAn.Protein > 30) {
                    tagsHTML += '<span class="tag high-protein">Giàu đạm</span>';
                }
                if (monAn.Sugar < 10) {
                    tagsHTML += '<span class="tag low-sugar">Ít đường</span>';
                }
                if (monAn.Carbs < 25) {
                    tagsHTML += '<span class="tag low-carb">Ít Carb</span>';
                }
                if (monAn.Fat < 10) {
                    tagsHTML += '<span class="tag low-fat">Ít béo</span>';
                }
                if (monAn.Fiber > 7) {
                    tagsHTML += '<span class="tag high-fiber">Giàu chất xơ</span>';
                }
                if (monAn.Calories < 450) {
                    tagsHTML += '<span class="tag low-calorie">Ít Calo</span>';
                }
                if (monAn.Protein > 20 && monAn.Carbs > 20 && monAn.Fat < 25) {
                    tagsHTML += '<span class="tag balanced">Bữa ăn cân bằng</span>';
                }
                return tagsHTML;
            }

            // Script giỏ hàng
            (function() {
                function renderCart() {
                    const cart = JSON.parse(localStorage.getItem('cart') || '[]');
                    const cartItemsDiv = document.getElementById('cartItems');
                    const summaryDiv = document.getElementById('summaryDetails');
                    let subTotal = 0;

                    if (cartItemsDiv && summaryDiv) {
                        cartItemsDiv.innerHTML = '';
                        cart.forEach(item => {
                            const lineTotal = item.price * item.quantity;
                            subTotal += lineTotal;
                            cartItemsDiv.insertAdjacentHTML('beforeend', `
                        <div class="cart-item" data-id="${item.id}">
                            <img src="${item.image}" alt="${item.name}">
                            <div class="item-name"><h4>${item.name}</h4></div>
                            <div class="quantity-control">
                                <button class="decrease">−</button>
                                <span>${item.quantity}</span>
                                <button class="increase">+</button>
                            </div>
                            <div class="item-price">${lineTotal.toLocaleString('vi-VN')}₫</div>
                        </div>
                    `);
                        });

                        const shippingFee = 30000;
                        const total = subTotal + shippingFee;
                        summaryDiv.innerHTML = `
                    <div class="summary-row"><span>Tổng Phụ</span><span>${subTotal.toLocaleString('vi-VN')}₫</span></div>
                    <div class="summary-row"><span>Phí Vận Chuyển</span><span>${shippingFee.toLocaleString('vi-VN')}₫</span></div>
                    <div class="summary-row total"><span>Tổng Cộng</span><span>${total.toLocaleString('vi-VN')}₫</span></div>
                `;
                    }

                    const cartCount = document.getElementById('cartCount');
                    if (cartCount) {
                        cartCount.textContent = cart.reduce((sum, item) => sum + item.quantity, 0);
                        cartCount.style.display = cart.length > 0 ? 'inline-block' : 'none';
                    }

                    if (cartItemsDiv) {
                        document.querySelectorAll('.cart-item').forEach(el => {
                            const id = el.dataset.id;
                            const btnDec = el.querySelector('.decrease');
                            const btnInc = el.querySelector('.increase');
                            const qtySpan = el.querySelector('span');

                            btnDec.replaceWith(btnDec.cloneNode(true));
                            btnInc.replaceWith(btnInc.cloneNode(true));

                            el.querySelector('.decrease').addEventListener('click', () => updateQty(id, -1, qtySpan));
                            el.querySelector('.increase').addEventListener('click', () => updateQty(id, +1, qtySpan));
                        });
                    }
                }

                function updateQty(id, delta, span) {
                    const cart = JSON.parse(localStorage.getItem('cart') || '[]');
                    const idx = cart.findIndex(i => i.id === id);
                    if (idx === -1) return;
                    cart[idx].quantity = Math.max(1, cart[idx].quantity + delta);
                    localStorage.setItem('cart', JSON.stringify(cart));
                    renderCart();
                    // Kích hoạt sự kiện tùy chỉnh
                    window.dispatchEvent(new CustomEvent('cartUpdated'));
                }

                // document.getElementById('placeOrderBtn').addEventListener('click', () => {
                //     const cart = JSON.parse(localStorage.getItem('cart') || '[]');
                //     if (!cart.length) {
                //         alert('Giỏ hàng của bạn đang trống!');
                //         return;
                //     }
                //     window.location.href = '/CoSo/php/checkout.php';
                // });

                renderCart();
            })();

            function updateCartBadge() {
                const cart = JSON.parse(localStorage.getItem('cart') || '[]');
                const totalCount = cart.reduce((sum, item) => sum + (item.quantity || 0), 0);
                const badge = document.getElementById('cartCount');
                if (badge) {
                    badge.textContent = totalCount;
                    badge.style.display = totalCount > 0 ? 'inline-block' : 'none';
                }
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
                            <i class="fas fa-shopping-cart" style="
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
                                <button onclick="triggerLoginFromMenu()" style="
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

            function triggerLoginFromMenu() {
                closeLoginAlert();

                // Logic tương tự index.js để hiển thị form đăng nhập
                const wrapper = document.querySelector(".wrapper");
                const closeContainerForm = document.querySelector(".login_regiter_form");

                if (wrapper && closeContainerForm) {
                    wrapper.classList.add("active-popup");
                    closeContainerForm.style.display = "flex";
                }
            }

            function closeLoginAlert() {
                const alert = document.getElementById('loginAlert');
                if (alert) {
                    alert.remove();
                }
            }
        });
    </script>

</body>

</html>