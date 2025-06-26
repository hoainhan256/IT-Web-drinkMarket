<?php
session_start();
require_once '../manage/database.php';

// Kiểm tra quyền admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Bạn không có quyền truy cập!");
}

$sql = "SELECT c.id, c.content, c.created_at, p.id AS product_id, p.name AS product_name, u.username
        FROM comments c
        JOIN product p ON c.product_id = p.id
        JOIN users u ON c.user_id = u.id
        WHERE c.support = 1
        ORDER BY c.created_at DESC";


$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Bình luận cần hỗ trợ</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
  <style>
    body {
  min-height: 100vh; /* hoặc 100dvh */
  display: flex;
  flex-direction: column;

}

#footer {
  margin-top: auto;
}
</style>
</head>
<body class="bg-gray-100 p-6">
    <div id="header"></div>
  <h2 class="text-2xl font-bold text-blue-600 mb-6">🛠️ Bình luận cần hỗ trợ</h2>

  <?php if ($result && $result->num_rows > 0): ?>
    <div class="bg-white p-4 rounded shadow">
      <?php while ($row = $result->fetch_assoc()): ?>
        <a href="../home/product_detail.php?id=<?php echo $row['product_id']; ?>" class="block mb-4">
         
          <p class="text-gray-800 font-semibold"><?php echo htmlspecialchars($row['username']); ?> bình luận về <strong><?php echo htmlspecialchars($row['product_name']); ?></strong></p>
          <p class="text-gray-700"><?php echo htmlspecialchars($row['content']); ?></p>
          <p class="text-sm text-gray-500">Vào lúc: <?php echo $row['created_at']; ?></p>
        </div>
        </a>
      <?php endwhile; ?>
    </div>
  <?php else: ?>
    <p class="text-gray-500">Không có bình luận cần hỗ trợ nào.</p>
  <?php endif; ?>
  <div id="footer"></div>
</body>
</html>
    <script>
        // Kế thừa header từ layout.html
        fetch('../layout.php')
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

        // Kế thừa footer từ layout.html
        fetch('../layout.php')
          .then(response => response.text())
          .then(data => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(data, 'text/html');
            const footer = doc.querySelector('footer');
            document.getElementById('footer').innerHTML = footer.outerHTML;
          });

        
    </script>