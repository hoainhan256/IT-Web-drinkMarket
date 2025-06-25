<?php
session_start();
require_once '../manage/database.php';

if (!isset($_SESSION['user_id'])) {
    die("Bạn cần đăng nhập để thanh toán.");
}

$user_id = $_SESSION['user_id'];

// 1. Lấy thông tin giỏ hàng hiện tại
$sql = "SELECT ci.product_id, ci.quantity, p.price 
        FROM cart_items ci 
        JOIN carts c ON ci.cart_id = c.id 
        JOIN product p ON ci.product_id = p.id 
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$order_items = [];
$total = 0;

while ($row = $result->fetch_assoc()) {
    $order_items[] = $row;
    $total += $row['price'] * $row['quantity'];
}

if (empty($order_items)) {
    die("Không có sản phẩm trong giỏ hàng.");
}

// 2. Lưu vào bảng `orders`
$stmt = $conn->prepare("INSERT INTO orders (user_id, total) VALUES (?, ?)");
$stmt->bind_param("id", $user_id, $total);
$stmt->execute();
$order_id = $stmt->insert_id;

// 3. Lưu vào bảng `order_items`
$stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
foreach ($order_items as $item) {
    $stmt->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
    $stmt->execute();
}

// 4. Xóa giỏ hàng cũ
$conn->query("DELETE FROM cart_items WHERE cart_id = (SELECT id FROM carts WHERE user_id = $user_id)");

header("Location: ../history/order_his.php");
exit;
?>
