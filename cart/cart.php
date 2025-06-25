<?php
session_start();
require_once '../manage/database.php';

if (!isset($_SESSION['user_id'])) {
    die("Bạn chưa đăng nhập.");
}

$user_id = $_SESSION['user_id'];

// Lấy cart_id theo user_id
$cart_sql = "SELECT id FROM carts WHERE user_id = ?";
$cart_stmt = $conn->prepare($cart_sql);
if (!$cart_stmt) {
    die("Lỗi prepare carts: " . $conn->error);
}
$cart_stmt->bind_param("i", $user_id);
$cart_stmt->execute();
$cart_result = $cart_stmt->get_result();
$cart = $cart_result->fetch_assoc();

if (!$cart) {
    die("Chưa có giỏ hàng cho người dùng này.");
}
$cart_id = $cart['id'];

// Truy vấn giỏ hàng
$sql = "SELECT ci.id AS cart_item_id, p.name, p.price, p.image_url, ci.quantity
        FROM cart_items ci
        JOIN product p ON ci.product_id = p.id
        WHERE ci.cart_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Lỗi prepare SQL: " . $conn->error);
}
$stmt->bind_param("i", $cart_id);
$stmt->execute();
$result = $stmt->get_result();
?>


<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>drinkMART - Giỏ hàng</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="cart.css" />
</head>
<body class="flex flex-col min-h-screen bg-white">
  <div id="header"></div>

  <main class="cart-section px-4 py-10 max-w-6xl mx-auto">
    <h2 class="text-2xl font-bold text-blue-500 mb-6">🛒 Giỏ hàng của bạn</h2>
    <table class="cart-table w-full text-left border">
      <thead>
        <tr class="bg-blue-100">
          <th class="p-3">Sản phẩm</th>
          <th class="p-3">Giá</th>
          <th class="p-3">Số lượng</th>
          <th class="p-3">Thành tiền</th>
          <th class="p-3">Thao tác</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        $total = 0;
        while ($row = $result->fetch_assoc()): 
          $subtotal = $row['price'] * $row['quantity'];
          $total += $subtotal;
        ?>
        <tr class="border-t">
          <td class="p-3 flex items-center space-x-3">
            <img src="<?php echo $row['image_url']; ?>" class="w-12 h-12 object-cover rounded">
            <span><?php echo htmlspecialchars($row['name']); ?></span>
          </td>
          <td class="p-3"><?php echo number_format($row['price'], 0, ',', '.'); ?> ₫</td>
          <td class="p-3">
            <input type="number" min="1" value="<?php echo $row['quantity']; ?>" 
                   data-id="<?php echo $row['cart_item_id']; ?>"
                   class="quantity-input w-16 border px-2 py-1 rounded">
          </td>
          <td class="p-3"><?php echo number_format($subtotal, 0, ',', '.'); ?> ₫</td>
          <td class="p-3">
            <button class="btn-delete text-red-600 hover:text-red-800" 
                    data-id="<?php echo $row['cart_item_id']; ?>">
              🗑</button>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

    <div class="cart-footer mt-8 flex justify-between items-center">
      <a href="../home/index.php" class="text-blue-500 hover:underline">← Tiếp tục mua sắm</a>
      <div class="total text-lg font-bold">
        Tổng cộng: <?php echo number_format($total, 0, ',', '.'); ?> ₫
        <form action="../cart/checkout.php" method="POST" class="inline">
          <button type="submit" class="btn-checkout ml-4 px-6 py-2 bg-green-500 text-white rounded hover:bg-green-600">
    Thanh toán
  </button>
</form>
      </div>
    </div>
  </main>

  <div id="footer"></div>

  <script>
    // Kế thừa header
    fetch('../layout.php')
      .then(res => res.text())
      .then(data => {
        const doc = new DOMParser().parseFromString(data, 'text/html');
        document.getElementById('header').innerHTML = doc.querySelector('header').outerHTML;
      });

    // Kế thừa footer
    fetch('../layout.php')
      .then(res => res.text())
      .then(data => {
        const doc = new DOMParser().parseFromString(data, 'text/html');
        document.getElementById('footer').innerHTML = doc.querySelector('footer').outerHTML;
      });

    // Xử lý xóa sản phẩm khỏi giỏ
    document.querySelectorAll('.btn-delete').forEach(button => {
      button.addEventListener('click', () => {
        const id = button.dataset.id;
        fetch('remove_item.php', {
          method: 'POST',
          headers: {'Content-Type': 'application/x-www-form-urlencoded'},
          body: 'id=' + id
        }).then(() => location.reload());
      });
    });

    // Xử lý cập nhật số lượng
    document.querySelectorAll('.quantity-input').forEach(input => {
      input.addEventListener('change', () => {
        const id = input.dataset.id;
        const quantity = input.value;
        fetch('update_quantity.php', {
          method: 'POST',
          headers: {'Content-Type': 'application/x-www-form-urlencoded'},
          body: 'id=' + id + '&quantity=' + quantity
        }).then(() => location.reload());
      });
    });
  </script>
</body>
</html>