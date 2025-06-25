<?php
session_start();
require_once '../manage/database.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
  header("Location: ../login/login.php");
  exit;
}

$user_id = $_SESSION['user_id'];

// Truy vấn lịch sử đơn hàng
$sql = "SELECT o.id AS order_id, o.created_at, o.total,
               p.name AS product_name, oi.price, oi.quantity
        FROM orders o
        JOIN order_items oi ON o.id = oi.order_id
        JOIN product p ON oi.product_id = p.id
        WHERE o.user_id = ?
        ORDER BY o.created_at DESC";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Lỗi prepare SQL: " . $conn->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Gom đơn hàng theo order_id
$orders = [];
while ($row = $result->fetch_assoc()) {
  $order_id = $row['order_id'];
  if (!isset($orders[$order_id])) {
    $orders[$order_id] = [
      'created_at' => $row['created_at'],
      'total' => $row['total'],
      'items' => []
    ];
  }
  $orders[$order_id]['items'][] = [
    'name' => $row['product_name'],
    'quantity' => $row['quantity'],
    'price' => $row['price']
  ];
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lịch sử đơn hàng - drinkMART</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../style.css">
</head>
<body class="bg-white min-h-screen flex flex-col">
  <div id="header"></div>

  <main class="flex-grow max-w-6xl mx-auto p-6">
    <h2 class="text-2xl font-bold text-blue-600 mb-6">📦 Lịch sử mua hàng</h2>

    <?php if (empty($orders)): ?>
      <p class="text-gray-600">Bạn chưa có đơn hàng nào.</p>
    <?php else: ?>
      <?php foreach ($orders as $order_id => $order): ?>
        <div class="mb-6 border border-gray-200 rounded-lg p-4 shadow">
          <div class="flex justify-between items-center mb-2">
            <span class="font-semibold text-blue-500">Mã đơn hàng: #<?php echo $order_id; ?></span>
            <span class="text-sm text-gray-500">Ngày: <?php echo $order['created_at']; ?></span>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
              <thead class="bg-gray-100">
                <tr>
                  <th class="p-2">Sản phẩm</th>
                  <th class="p-2">Số lượng</th>
                  <th class="p-2">Giá</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($order['items'] as $item): ?>
                  <tr class="border-t">
                    <td class="p-2"><?php echo htmlspecialchars($item['name']); ?></td>
                    <td class="p-2"><?php echo $item['quantity']; ?></td>
                    <td class="p-2"><?php echo number_format($item['price'], 0, ',', '.'); ?> ₫</td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
          <div class="text-right font-semibold mt-3 text-blue-600">
            Tổng cộng: <?php echo number_format($order['total'], 0, ',', '.'); ?> ₫
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </main>

  <div id="footer"></div>

  <script>
    fetch('../layout.php').then(res => res.text()).then(data => {
      const doc = new DOMParser().parseFromString(data, 'text/html');
      document.getElementById('header').innerHTML = doc.querySelector('header').outerHTML;
      document.getElementById('footer').innerHTML = doc.querySelector('footer').outerHTML;
    });
  </script>
</body>
</html>
