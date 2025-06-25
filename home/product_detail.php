<?php
require_once '../manage/database.php';

if (!isset($_GET['id'])) {
    echo "Không tìm thấy sản phẩm!";
    exit;
}

$id = intval($_GET['id']);
$sql = "SELECT * FROM product WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Sản phẩm không tồn tại!";
    exit;
}

$product = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title><?php echo htmlspecialchars($product['name']); ?> - Chi tiết sản phẩm</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-blue-50">
    <div id="header"></div>
  <div class="max-w-4xl mx-auto p-6 bg-white rounded-xl shadow-lg mt-10">
    <div class="flex flex-col md:flex-row items-center space-x-8">
      <img src="<?php echo $product['image_url']; ?>" alt="Ảnh sản phẩm" class="w-60 h-auto rounded-xl mb-6 md:mb-0">
      <div>
        <h1 class="text-2xl font-bold text-blue-600 mb-2"><?php echo htmlspecialchars($product['name']); ?></h1>
        <p class="text-gray-700 mb-2"><strong>Giá:</strong> <?php echo number_format($product['price'], 0, ',', '.'); ?> VNĐ</p>
        <p class="text-gray-600"><strong>Mô tả:</strong> <?php echo htmlspecialchars($product['description'] ?? "Chưa có mô tả"); ?></p>
        <a href="../home/index.php" class="inline-block mt-6 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">← Quay về trang chủ</a>
        <button 
          class="mt-2 px-4 py-1 text-white bg-green-500 rounded hover:bg-green-600"
          onclick="addToCart(<?php echo $product['id']; ?>)">
          Thêm vào giỏ hàng
        </button>
      </div>
    </div>
  </div>
    <div id="footer"></div>
</body>
</html>
<script>
    // Tải header
    fetch('../layout.php') // Đường dẫn sửa thành ../layout.html
      .then(response => response.text())
      .then(data => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(data, 'text/html');
        const header = doc.querySelector('header');
        document.getElementById('header').innerHTML = header.outerHTML;

        // Gán lại sự kiện cho menu toggle sau khi tải header
        document.querySelector('.menu-toggle').addEventListener('click', () => {
          document.querySelector('.main-menu').classList.toggle('hidden');
        });
      });

    // Tải footer
    fetch('../layout.php') // Đường dẫn sửa thành ../layout.html
      .then(response => response.text())
      .then(data => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(data, 'text/html');
        const footer = doc.querySelector('footer');
        document.getElementById('footer').innerHTML = footer.outerHTML;
      });
      
  </script>
    <script>
function addToCart(productId) {
  fetch('../cart/add_to_cart.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: `product_id=${productId}&quantity=1`
  })
  .then(response => response.json())
  .then(data => {
    alert(data.message);
  })
  .catch(error => {
    console.error('Lỗi:', error);
  });
}

</script>