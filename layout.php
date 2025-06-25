<?php
session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>DrinkMART</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="style.css" />
</head>
<body class="flex flex-col min-h-screen bg-gradient-to-b from-blue-50 to-blue-100">
  <!-- Header -->
  <header class="header bg-blue-100 shadow-md py-4 px-6 flex justify-between items-center z-10">
  <div class="logo-section flex items-center">
    <i class="fas fa-bars menu-toggle mr-4 cursor-pointer text-blue-400 md:hidden"></i>
    <!-- Bọc logo và chữ trong link về trang chủ -->
    <a href="/drink_marketplace/home/index.php" class="flex items-center">
      <img src="https://png.pngtree.com/element_our/20190522/ourlarge/pngtree-shopping-cart-icon-design-image_1071385.jpg" alt="logo" class="logo-img w-10 h-10">
      <span class="logo-text text-2xl font-extrabold ml-2 text-blue-500">DrinkMART</span>
    </a>
  </div>
  <!-- Thanh tìm kiếm -->
<form action="#" method="GET" class="hidden md:flex items-center ml-6 flex-1 max-w-md">
  <div class="flex w-full">
    <input
      type="text"
      name="search"
      placeholder="Tìm kiếm..."
      class="flex-grow px-4 py-2 rounded-l-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-300 text-gray-700 bg-white"
    />
    <button type="submit"
      class="bg-blue-400 hover:bg-blue-500 transition-colors rounded-r-lg px-5 flex items-center justify-center text-white text-xl font-bold border-t border-b border-r border-gray-300">
      <i class="fas fa-search"></i>
    </button>
  </div>
</form>
<div class="icons flex items-center space-x-4">
  <a href="../cart/cart.php">
  <i class="fas fa-shopping-cart cursor-pointer text-blue-500 hover:text-blue-700"> 
     
  </i>
  </a>

  <?php if (isset($_SESSION['username'])): ?>
    <span class="text-gray-700 font-semibold hidden md:inline-block">
      👋 Xin chào, <?= htmlspecialchars($_SESSION['ho_ten']) ?>
    </span>
    
    <a href="../login/logout.php" class="ml-4 px-4 py-2 bg-red-500 text-white font-bold rounded-lg hover:bg-red-600 transition hidden md:inline-block">Đăng xuất</a>
  <?php else: ?>
    <a href="../login/login.php" class="ml-4 px-4 py-2 bg-blue-500 text-white font-bold rounded-lg hover:bg-blue-700 transition hidden md:inline-block">Đăng nhập</a>
  <?php endif; ?>
</div>
</header>


  <!-- Footer -->
  <footer class="footer bg-blue-100 text-gray-700 py-6">
    <div class="footer-container mx-auto max-w-6xl grid grid-cols-1 md:grid-cols-3 gap-6 px-4">
      <div class="footer-section">
        <h3 class="footer-title text-lg font-bold mb-3 text-blue-500">drinkMART</h3>
        <p class="text-gray-600 text-sm">Cung cấp nước uống đa dạng cho mọi người.</p>
      </div>
      <div class="footer-section">
        <h3 class="footer-title text-lg font-bold mb-3 text-blue-500">Liên kết nhanh</h3>
        <ul class="footer-links space-y-2 text-sm">
          <li><a href="#" class="text-gray-600 hover:text-blue-400">Chính sách bảo mật</a></li>
          <li><a href="#" class="text-gray-600 hover:text-blue-400">Điều khoản dịch vụ</a></li>
          <li><a href="#" class="text-gray-600 hover:text-blue-400">Hỗ trợ khách hàng</a></li>
          <li><a href="#" class="text-gray-600 hover:text-blue-400">Liên hệ</a></li>
        </ul>
      </div>
      <div class="footer-section">
        <h3 class="footer-title text-lg font-bold mb-3 text-blue-500">Theo dõi chúng tôi</h3>
        <div class="social-icons flex space-x-4">
          <a href="#" class="text-blue-400 hover:text-blue-600"><i class="fab fa-facebook-f"></i></a>
          <a href="#" class="text-blue-400 hover:text-blue-600"><i class="fab fa-tiktok"></i></a>
          <a href="#" class="text-blue-400 hover:text-blue-600"><i class="fab fa-twitter"></i></a>
          <a href="#" class="text-blue-400 hover:text-blue-600"><i class="fab fa-youtube"></i></a>
        </div>
      </div>
    </div>
    <div class="footer-bottom bg-blue-200 py-3 mt-6">
      <p class="text-center text-gray-600 text-sm">© 2025 drinkMART. All rights reserved.</p>
    </div>
  </footer>

  <script>
    // Toggle menu on mobile
    document.querySelector('.menu-toggle').addEventListener('click', () => {
      document.querySelector('.main-menu').classList.toggle('hidden');
    });
  </script>
</body>
</html>