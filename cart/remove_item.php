<?php
session_start();
require_once '../manage/database.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo "Chưa đăng nhập.";
    exit;
}

$id = $_POST['id'] ?? 0;

$sql = "DELETE FROM cart_items WHERE id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    http_response_code(500);
    echo "Lỗi prepare: " . $conn->error;
    exit;
}

$stmt->bind_param("i", $id);
if ($stmt->execute()) {
    echo "Xoá thành công";
} else {
    http_response_code(500);
    echo "Lỗi xoá: " . $stmt->error;
}
?>
