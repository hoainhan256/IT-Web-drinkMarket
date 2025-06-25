<?php
session_start();
require_once '../manage/database.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo "Chưa đăng nhập.";
    exit;
}

$id = $_POST['id'] ?? 0;
$quantity = $_POST['quantity'] ?? 1;

if ($quantity < 1) $quantity = 1;

$sql = "UPDATE cart_items SET quantity = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    http_response_code(500);
    echo "Lỗi prepare: " . $conn->error;
    exit;
}

$stmt->bind_param("ii", $quantity, $id);
if ($stmt->execute()) {
    echo "OK";
} else {
    http_response_code(500);
    echo "Lỗi update: " . $stmt->error;
}
?>
