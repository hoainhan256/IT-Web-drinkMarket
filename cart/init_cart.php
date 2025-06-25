<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../manage/database.php';
if (!$conn) {
    die("Không kết nối được DB: " . mysqli_connect_error());
}
// Chỉ chạy nếu đã đăng nhập
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login/login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Kiểm tra giỏ hàng đã có chưa
$stmt = $conn->prepare("SELECT id FROM carts WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($cart_id);
    $stmt->fetch();
    $_SESSION['cart_id'] = $cart_id;
} else {
    // Chưa có => tạo mới
    $stmt_insert = $conn->prepare("INSERT INTO carts (user_id) VALUES (?)");
    $stmt_insert->bind_param("i", $user_id);
    $stmt_insert->execute();
    $new_cart_id = $stmt_insert->insert_id;

    $_SESSION['cart_id'] = $new_cart_id;
    $stmt_insert->close();
}

$stmt->close();

// Option: redirect về trang trước hoặc trang chủ
header('Location: ../home/index.php');
exit;
?>
