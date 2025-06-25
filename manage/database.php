<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'marketplace';
$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Đọc nội dung file SQL
//  $sql = file_get_contents('cart.sql');

// if ($conn->multi_query($sql)) {
//     echo "Tạo bảng và thêm dữ liệu thành công!";
// } else {
//     echo "Lỗi khi thực thi SQL: " . $conn->error;
// }
// $product = file_get_contents('products.sql');

// if ($conn->multi_query($product)) {
//     echo "Tạo bảng và thêm dữ liệu thành công!";
// } else {
//     echo "Lỗi khi thực thi SQL: " . $conn->error;
// }
//  $conn->close();

?>
