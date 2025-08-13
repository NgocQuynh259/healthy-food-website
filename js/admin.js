// Toggle
const body = document.querySelector("body");
const modeToggle = body.querySelector(".mode-toggle");
const sidebar = body.querySelector("header");
const sidebarToggle = body.querySelector(".sidebar-toggle");

let getStatus = localStorage.getItem("status");
if (getStatus === "close") sidebar.classList.add("close");

sidebarToggle.addEventListener("click", () => {
    sidebar.classList.toggle("close");
    localStorage.setItem(
        "status",
        sidebar.classList.contains("close") ? "close" : "open"
    );
});

// Hiển thị thời gian
function updateTime() {
    const now = new Date();
    const options = {
        hour: "2-digit",
        minute: "2-digit",
        day: "2-digit",
        month: "2-digit",
        year: "numeric",
        weekday: "short",
    };
    document.getElementById("datetime").textContent = now.toLocaleString(
        "vi-VN",
        options
    );
}
setInterval(updateTime, 1000);
updateTime();

// Mở modal thêm sản phẩm
const openAddBtn = document.getElementById("openAddFormBtn");
const addModal = document.getElementById("addFormPopup");
const addCloseBtn = document.querySelector("#addFormPopup .close_btn");
const addForm = addModal.querySelector("form");

openAddBtn.onclick = () => {
    addModal.style.display = "block";
};
addCloseBtn.onclick = () => (addModal.style.display = "none");
window.onclick = (e) => {
    if (e.target === addModal) addModal.style.display = "none";
};

addForm.onsubmit = (e) => {
    e.preventDefault();
    const formData = new FormData(addForm);

    const thanhphanInput = addForm.querySelector("#thanhphan").value.trim();
    const thanhphans = thanhphanInput
        .split(",")
        .map((tp) => tp.trim())
        .filter((tp) => tp);
    formData.append("thanhphans", JSON.stringify(thanhphans));

    fetch("xuly_admin.php", {
        method: "POST",
        body: formData,
    })
        .then((res) => res.json())
        .then((resp) => {
            if (resp.success) {
                showSuccess("Thêm sản phẩm thành công!");
                addModal.style.display = "none";
                addForm.reset();
                loadProducts();
            } else {
                showError("Lỗi khi thêm sản phẩm: " + resp.message);
            }
        })
        .catch((err) => {
            console.error("Lỗi kết nối:", err);
            showError("Có lỗi xảy ra khi thêm sản phẩm.");
        });
};

// Load danh sách sản phẩm và render bảng
function loadProducts() {
    fetch("get_products.php")
        .then((res) => res.json())
        .then((data) => renderTable(data))
        .catch((err) => console.error("Lỗi tải dữ liệu:", err));
}
document.addEventListener("DOMContentLoaded", loadProducts);

function renderTable(products) {
    const tbody = document.querySelector("#productTable tbody");
    tbody.innerHTML = "";
    products.forEach((p) => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
            <td>${p.Masp || "N/A"}</td>
            <td>${p.Tensp || "N/A"}</td>
            <td>${p.Maloai || "N/A"}</td>
            <td>${p.Gianguyenlieu || "N/A"}</td>
            <td>${p.Giaban || "N/A"}</td>
            <td><span class="status-pill ${
                Number(p.Trangthai) === 1 ? "active" : "inactive"
            }">${Number(p.Trangthai) === 1 ? "Còn hàng" : "Hết hàng"}</span></td>

            <td>
                <button class="btn-edit" data-product='${JSON.stringify(
                    p
                )}'>Chỉnh sửa</button>
                <button class="btn-delete" data-masp="${p.Masp}">🗑️</button>
            </td>`;
        tbody.appendChild(tr);
    });

    // Thêm sự kiện
    document.querySelectorAll(".btn-edit").forEach((btn) => {
        btn.addEventListener("click", showEditPopup);
    });

    document.querySelectorAll(".btn-delete").forEach((btn) => {
        btn.addEventListener("click", handleDeleteProduct);
    });
}

function showEditPopup(e) {
    const modal = document.getElementById("editFormPopup");
    const form = modal.querySelector("#editForm");
    const product = JSON.parse(e.target.dataset.product);

    form.querySelector("#ma_sp").value = product.Masp || "";
    form.querySelector("#ten_sp").value = product.Tensp || "";
    form.querySelector("#maloai").value = product.Maloai || "";
    form.querySelector("#trangthai").value = product.Trangthai || "1";
    form.querySelector("#gianguyenlieu").value = product.Gianguyenlieu || "";
    form.querySelector("#giaban").value = product.Giaban || "";
    form.querySelector("#mota").value = product.Mota || "";
    form.querySelector("#calo").value = product.Calories || "";
    form.querySelector("#protein").value = product.Protein || "";
    form.querySelector("#fat").value = product.Fat || "";
    form.querySelector("#carbs").value = product.Carbs || "";
    form.querySelector("#sugar").value = product.Sugar || "";
    form.querySelector("#fiber").value = product.Fiber || "";

    fetch(`get_thanhphan.php?masp=${product.Masp}`)
        .then((res) => res.json())
        .then((data) => {
            form.querySelector("#thanhphan").value = Array.isArray(data)
                ? data.join(", ")
                : data;
        })
        .catch((err) => {
            console.error("Lỗi tải thành phần:", err);
            form.querySelector("#thanhphan").value = "";
        });

    modal.style.display = "block";
    modal.querySelector(".close_btn").onclick = () => {
        modal.style.display = "none";
        form.reset();
    };

    form.onsubmit = (event) => {
        event.preventDefault();
        const formData = new FormData(form);
        const thanhphanList = form
            .querySelector("#thanhphan")
            .value.split(",")
            .map((x) => x.trim())
            .filter(Boolean);
        formData.append("thanhphans", JSON.stringify(thanhphanList));

        fetch("update_product.php", {
            method: "POST",
            body: formData,
        })
            .then((res) => res.json())
            .then((resp) => {
                if (resp.success) {
                    showSuccess("Cập nhật thành công!");
                    modal.style.display = "none";
                    loadProducts();
                } else {
                    showError("Lỗi cập nhật: " + resp.message);
                }
            })
            .catch((err) => {
                console.error("Lỗi khi gửi dữ liệu:", err);
                showError("Có lỗi xảy ra.");
            });
    };
}

function handleDeleteProduct(e) {
    const masp = e.target.dataset.masp;
    if (!masp) return;

    confirmAsync("Bạn có chắc chắn muốn xoá sản phẩm này không?").then((result) => {
        if (result) {
            fetch("../php/delete_product.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ masp: masp })
            })
                .then(res => res.json())
                .then(resp => {
                    if (resp.success) {
                        showSuccess("Xoá thành công!");
                        loadProducts();
                    } else {
                        showError("Lỗi xoá: " + resp.message);
                    }
                })
                .catch(err => {
                    console.error("Lỗi khi xoá:", err);
                    showError("Có lỗi xảy ra khi xoá sản phẩm.");
                });
        }
    });
}
