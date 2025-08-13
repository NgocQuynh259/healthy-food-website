// Chon che do an

document.addEventListener("DOMContentLoaded", () => {
    const boxes = document.querySelectorAll(".form_img_box");

    boxes.forEach((box) => {
        box.addEventListener("click", () => {
            box.classList.toggle("selected"); // Chọn hoặc bỏ chọn
        });
    });
});

document.querySelector(".submit-form").addEventListener("click", function () {
    // Lấy dữ liệu từng trường
    const weight = document.getElementById("weight").value;
    const height = document.getElementById("height").value;
    const age = document.getElementById("age").value;
    const gender = document.querySelector('input[name="gender"]:checked').value;
    const activityLevel = document.getElementById("activityLevel").value;
    const goal = document.querySelector('input[name="goal"]:checked').value;

    // Lấy chế độ ăn
    const selectedDiets = [];
    document.querySelectorAll(".form_img_box.selected").forEach((box) => {
        selectedDiets.push(box.getAttribute("data-value"));
    });

    // Lấy dị ứng
    const allergies = [];
    document
        .querySelectorAll('input[name="diung[]"]:checked')
        .forEach((input) => {
            allergies.push(input.value);
        });

    // Lấy mã khách hàng nếu cần (ví dụ lưu trong biến JS khi đăng nhập)
    const makh = window.makh || "KH01"; // chỉ là ví dụ, bạn thay bằng thực tế
    console.log("Diets:", selectedDiets); // phải có dữ liệu
    console.log("Allergies:", allergies); // phải có dữ liệu

    // Gửi về PHP backend
    fetch("xulyForm.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            makh,
            weight,
            height,
            age,
            gender,
            activityLevel,
            goal,
            diets: selectedDiets,
            allergies: allergies,
        }),
    })
        .then((res) => res.json())
        .then((result) => {
            if (result.status === "success") {
                showSuccess(result.message);
            } else {
                showError(result.message);
            }
            document.querySelector(".multi_form").style.display = "none";
        });
});
