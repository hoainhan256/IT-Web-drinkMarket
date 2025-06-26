<?php
session_start();
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
// 1. Lấy toàn bộ sản phẩm (ID, name, description)
$all_products = [];
$sql = "SELECT id, name, description FROM product";
$result_all = $conn->query($sql);

while ($row = $result_all->fetch_assoc()) {
    $all_products[$row['id']] = strtolower($row['name'] . ' ' . $row['description']);
}

$product = $result->fetch_assoc();
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['comment']) && isset($_SESSION['user_id'])) {
    $comment = trim($_POST['comment']);
    $user_id = $_SESSION['user_id'];

    // Gọi AI phân loại comment
    $escaped_comment = escapeshellarg($comment);  // bảo mật đầu vào
    $cmd = "python predict.py $escaped_comment";
    $output = shell_exec($cmd);
    $support = (int)trim($output);

    // Thêm vào DB với kết quả phân loại
    $stmt = $conn->prepare("INSERT INTO comments (product_id, user_id, content, support) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iisi", $id, $user_id, $comment, $support);
    $stmt->execute();
}

// Hàm tính TF-IDF + Cosine similarity
function text_to_vector($text) {
    $words = preg_split('/\W+/', strtolower($text), -1, PREG_SPLIT_NO_EMPTY);
    $vector = [];
    foreach ($words as $word) {
        if (!isset($vector[$word])) $vector[$word] = 0;
        $vector[$word]++;
    }
    return $vector;
}

function cosine_similarity($vec1, $vec2) {
    $intersection = array_intersect_key($vec1, $vec2);
    $dot_product = 0;
    foreach ($intersection as $key => $val) {
        $dot_product += $vec1[$key] * $vec2[$key];
    }

    $norm1 = sqrt(array_sum(array_map(fn($v) => $v * $v, $vec1)));
    $norm2 = sqrt(array_sum(array_map(fn($v) => $v * $v, $vec2)));

    if ($norm1 == 0 || $norm2 == 0) return 0;

    return $dot_product / ($norm1 * $norm2);
}
$current_text = $product['name'] . ' ' . $product['description'];
$current_vec = text_to_vector($current_text);

$similarities = [];

foreach ($all_products as $pid => $text) {
    if ($pid == $product['id']) continue; // bỏ chính nó
    $vec = text_to_vector($text);
    $similarities[$pid] = cosine_similarity($current_vec, $vec);
}

// Sắp xếp theo độ tương đồng giảm dần
arsort($similarities);
$top_3_ids = array_slice(array_keys($similarities), 0, 3);

// Lấy thông tin chi tiết để hiển thị
$placeholders = implode(',', array_fill(0, count($top_3_ids), '?'));
$types = str_repeat('i', count($top_3_ids));
$stmt = $conn->prepare("SELECT id, name, price, image_url FROM product WHERE id IN ($placeholders)");
$stmt->bind_param($types, ...$top_3_ids);
$stmt->execute();
$related_result = $stmt->get_result();

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
  <div class="mt-8 border-t pt-6">
  <h3 class="text-xl font-bold text-gray-800 mb-4">💬 Bình luận</h3>

  <!-- Nếu chưa đăng nhập -->
<?php if (!isset($_SESSION['user_id'])): ?>
  <p class="text-red-500 mb-4">Vui lòng <a href="../login/login.php" class="text-blue-500 underline">đăng nhập</a> để bình luận.</p>
<?php else: ?>
<form method="POST" action="../comments/classify_comment.php" class="mb-6">
  <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
  <textarea name="comment" rows="3" class="w-full border p-3 rounded" placeholder="Viết bình luận..." required></textarea>
  <button type="submit" class="mt-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Gửi bình luận</button>
</form>
<?php endif; ?>

  <?php
    // Lấy bình luận sản phẩm
    $stmt = $conn->prepare("SELECT c.content, c.created_at, u.username 
                            FROM comments c 
                            JOIN users u ON c.user_id = u.id 
                            WHERE c.product_id = ? 
                            ORDER BY c.created_at DESC");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $comments_result = $stmt->get_result();

    if ($comments_result->num_rows === 0):
  ?>
    <p class="text-gray-500">Chưa có bình luận nào.</p>
  <?php else: ?>
    <?php while ($c = $comments_result->fetch_assoc()): ?>
      <div class="mb-4">
        <p class="text-sm text-gray-600 font-semibold"><?php echo htmlspecialchars($c['username']); ?> <span class="text-xs text-gray-400">(<?php echo $c['created_at']; ?>)</span></p>
        <p class="text-gray-800"><?php echo nl2br(htmlspecialchars($c['content'])); ?></p>
      </div>
    <?php endwhile; ?>
  <?php endif; ?>
</div>
<?php if ($related_result->num_rows > 0): ?>
  <div class="mt-10">
    <h3 class="text-xl font-bold text-gray-800 mb-4">🔍 Sản phẩm tương tự</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <?php while ($rel = $related_result->fetch_assoc()): ?>
        <div class="border rounded-lg p-4 bg-white shadow">
          <img src="<?php echo $rel['image_url']; ?>" class="w-full h-40 object-cover rounded mb-2">
          <h4 class="font-semibold text-blue-600"><?php echo htmlspecialchars($rel['name']); ?></h4>
          <p class="text-gray-700 mb-2"><?php echo number_format($rel['price'], 0, ',', '.'); ?> VNĐ</p>
          <a href="product_detail.php?id=<?php echo $rel['id']; ?>" class="text-blue-500 hover:underline">Xem chi tiết</a>
        </div>
      <?php endwhile; ?>
    </div>
  </div>
<?php endif; ?>

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