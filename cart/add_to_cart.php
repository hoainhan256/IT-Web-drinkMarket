<?php
session_start();
require_once '../manage/database.php';

header('Content-Type: application/json');

// 1. Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Bạn cần đăng nhập để thêm vào giỏ hàng.']);
    exit;
}

$user_id = $_SESSION['user_id'];

// 2. Lấy product_id và quantity từ request
if (!isset($_POST['product_id'])) {
    echo json_encode(['success' => false, 'message' => 'Thiếu product_id.']);
    exit;
}

$product_id = intval($_POST['product_id']);
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

// 3. Tìm hoặc tạo giỏ hàng (carts)
$stmt = $conn->prepare("SELECT id FROM carts WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Chưa có giỏ → tạo mới
    $stmt_create = $conn->prepare("INSERT INTO carts (user_id) VALUES (?)");
    $stmt_create->bind_param("i", $user_id);
    if (!$stmt_create->execute()) {
        echo json_encode(['success' => false, 'message' => 'Lỗi tạo giỏ hàng: ' . $stmt_create->error]);
        exit;
    }
    $cart_id = $stmt_create->insert_id;
} else {
    // Đã có → lấy ID
    $row = $result->fetch_assoc();
    $cart_id = $row['id'];
}

// 4. Kiểm tra sản phẩm đã có trong giỏ chưa
$stmt_check = $conn->prepare("SELECT id, quantity FROM cart_items WHERE cart_id = ? AND product_id = ?");
$stmt_check->bind_param("ii", $cart_id, $product_id);
$stmt_check->execute();
$res_check = $stmt_check->get_result();

if ($res_check->num_rows > 0) {
    // Đã có → cập nhật số lượng
    $item = $res_check->fetch_assoc();
    $new_quantity = $item['quantity'] + $quantity;

    $stmt_update = $conn->prepare("UPDATE cart_items SET quantity = ? WHERE id = ?");
    $stmt_update->bind_param("ii", $new_quantity, $item['id']);
    $stmt_update->execute();
} else {
    // Chưa có → thêm mới
    $stmt_insert = $conn->prepare("INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (?, ?, ?)");
    $stmt_insert->bind_param("iii", $cart_id, $product_id, $quantity);
    if (!$stmt_insert->execute()) {
        echo json_encode(['success' => false, 'message' => 'Lỗi thêm sản phẩm: ' . $stmt_insert->error]);
        exit;
    }
}

echo json_encode(['success' => true, 'message' => '🛒 Đã thêm vào giỏ hàng!']);
