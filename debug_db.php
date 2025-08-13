<?php
require_once 'php/connect.php';

echo "Checking database connection...\n";

// Kiểm tra số lượng sản phẩm
$result = $conn->query('SELECT COUNT(*) as count FROM sanpham');
if ($result) {
    $row = $result->fetch_assoc();
    echo "Total products in sanpham table: " . $row['count'] . "\n";
} else {
    echo "Error: " . $conn->error . "\n";
}

// Kiểm tra các category
echo "\nChecking categories...\n";
$result = $conn->query('SELECT DISTINCT Maloai FROM sanpham LIMIT 10');
if ($result) {
    while ($row = $result->fetch_assoc()) {
        echo "Category ID: " . $row['Maloai'] . "\n";
    }
} else {
    echo "Error: " . $conn->error . "\n";
}

// Kiểm tra một vài sản phẩm
echo "\nChecking some products...\n";
$result = $conn->query('SELECT Masp, Tensp, Calories, Maloai FROM sanpham LIMIT 5');
if ($result) {
    while ($row = $result->fetch_assoc()) {
        echo "Product: " . $row['Tensp'] . " - Calories: " . $row['Calories'] . " - Category: " . $row['Maloai'] . "\n";
    }
} else {
    echo "Error: " . $conn->error . "\n";
}

// Kiểm tra bảng khachhang_suckhoe
echo "\nChecking health data...\n";
$result = $conn->query('SELECT COUNT(*) as count FROM khachhang_suckhoe');
if ($result) {
    $row = $result->fetch_assoc();
    echo "Total health records: " . $row['count'] . "\n";
} else {
    echo "Error: " . $conn->error . "\n";
}

// Kiểm tra bảng suckhoe_loai  
echo "\nChecking health categories...\n";
$result = $conn->query('SELECT COUNT(*) as count FROM suckhoe_loai');
if ($result) {
    $row = $result->fetch_assoc();
    echo "Total health category records: " . $row['count'] . "\n";
} else {
    echo "Error: " . $conn->error . "\n";
}
?>
