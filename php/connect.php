<?php
$host = "127.0.0.1:3306";  
$user = "root";
$pass = ""; 
$db = "tqfood";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
?>
