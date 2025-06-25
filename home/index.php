<?php session_start(); 
require_once '../manage/database.php';

$sql = "SELECT * FROM product ORDER BY created_at DESC";
$search = $_GET['search'] ?? ''; // Lấy từ khóa tìm kiếm nếu có

if (!empty($search)) {
  // Nếu có từ khóa -> lọc sản phẩm theo tên
  $stmt = $conn->prepare("SELECT * FROM product WHERE name LIKE ? ORDER BY created_at DESC");
  $likeSearch = "%" . $search . "%";
  $stmt->bind_param("s", $likeSearch);
  $stmt->execute();
  $result = $stmt->get_result();
} else {
  // Nếu không có từ khóa -> hiện toàn bộ
  $sql = "SELECT * FROM product ORDER BY created_at DESC";
  $result = $conn->query($sql);
}

?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>drinkMART - Trang chủ</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="../style.css" /> <!-- Đường dẫn sửa thành ../style.css -->
</head>
<body class="flex flex-col min-h-screen bg-white">
  <!-- Placeholder cho header -->
  <div id="header"></div>

  <!-- Banner chính -->
 <section class="relative w-full h-96 md:h-[800px] flex items-center justify-center mb-8 overflow-hidden rounded-b-3xl shadow-lg">
  <!-- Hai ảnh chồng lên nhau, chỉ 1 ảnh hiện tại 1 thời điểm -->
  <img id="bannerImg1"
       src="https://cdn.pixabay.com/photo/2017/05/30/12/20/matcha-2356768_1280.jpg"
       alt="Banner 1"
       class="absolute inset-0 w-full h-full object-cover object-center scale-105 transition-opacity duration-1000 opacity-100 z-0" />
  <img id="bannerImg2"
       src="https://img1.kienthucvui.vn/uploads/2021/02/12/hinh-anh-ca-phe-dep-nhat_045434039.jpg"
       alt="Banner 2"
       class="absolute inset-0 w-full h-full object-cover object-center scale-105 transition-opacity duration-1000 opacity-0 z-0" />
  <!-- Lớp phủ làm mờ và tối dịu -->
  <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-black/20 to-transparent z-10"></div>
  <!-- Nội dung banner -->
  <div class="relative z-20 flex flex-col items-center text-center px-4">
    <h1 class="text-4xl md:text-5xl font-extrabold text-white drop-shadow-2xl mb-3 tracking-wide animate-fade-in-down">drinkMART</h1>
    <p class="text-2xl md:text-3xl font-semibold text-white drop-shadow-xl mb-6 animate-fade-in">Khám phá thế giới đồ uống chất lượng!</p>
    <a href="#products"
       class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-10 rounded-full text-lg shadow-xl transition-transform duration-300 hover:scale-105 animate-fade-in-up">Mua Ngay</a>
  </div>
  <style>
    @keyframes fade-in-down {
      from { opacity:0; transform: translateY(-30px);}
      to   { opacity:1; transform: translateY(0);}
    }
    @keyframes fade-in {
      from { opacity:0;}
      to   { opacity:1;}
    }
    @keyframes fade-in-up {
      from { opacity:0; transform: translateY(30px);}
      to   { opacity:1; transform: translateY(0);}
    }
    .animate-fade-in-down { animation: fade-in-down 1s cubic-bezier(.22,.61,.36,1) both;}
    .animate-fade-in { animation: fade-in 1.6s cubic-bezier(.22,.61,.36,1) both;}
    .animate-fade-in-up { animation: fade-in-up 1.2s cubic-bezier(.22,.61,.36,1) both;}
  </style>
  <script>
    // JS để chuyển động ảnh
    const img1 = document.getElementById('bannerImg1');
    const img2 = document.getElementById('bannerImg2');
    let showingFirst = true;

    setInterval(() => {
      if (showingFirst) {
        img1.style.opacity = "0";
        img2.style.opacity = "1";
      } else {
        img1.style.opacity = "1";
        img2.style.opacity = "0";
      }
      showingFirst = !showingFirst;
    }, 5000); // đổi ảnh mỗi 5 giây
  </script>
</section>


  <!-- Danh sách sản phẩm -->
   <?php if ($result && $result->num_rows > 0): ?>
  <!-- Hiển thị sản phẩm -->
<?php else: ?>
  <p class="col-span-4 text-center text-gray-500">
    Không tìm thấy sản phẩm phù hợp với từ khoá "<strong><?php echo htmlspecialchars($search); ?></strong>"
  </p>
<?php endif; ?>
  <section class="max-w-6xl mx-auto px-4 py-10">
  <h2 class="text-2xl md:text-3xl font-semibold text-blue-500 mb-10 text-center uppercase tracking-wide">
    Danh mục sản phẩm
  </h2>
<div id="productGrid" class="grid grid-cols-2 md:grid-cols-4 gap-8 items-end">
  <?php if ($result && $result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
      <div class="flex flex-col items-center">
       
        <div class="text-center">
          <a href="product_detail.php?id=<?php echo $row['id']; ?>" class="block text-center">
            <img src="<?php echo $row['image_url']; ?>" class="h-28 w-auto mb-3 mx-auto">
            <h3 class="font-semibold text-gray-800 text-base mb-1 uppercase"><?php echo htmlspecialchars($row['name']); ?></h3>
            <span class="text-gray-500 text-xs"><?php echo number_format($row['price'], 0, ',', '.'); ?> VNĐ</span>
          </a>
          <span class="text-gray-500 text-xs">
          </span>
       <button 
          class="mt-2 px-4 py-1 text-white bg-green-500 rounded hover:bg-green-600"
          onclick="addToCart(<?php echo $row['id']; ?>)">
          Thêm vào giỏ hàng
        </button>

        </div>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p class="col-span-4 text-center text-gray-500">Chưa có sản phẩm nào được thêm.</p>
  <?php endif; ?>
</div>


  <!-- Nút xem thêm -->
  <div class="flex justify-center mt-8">
    <button
      id="showMoreBtn"
      class="px-8 py-2 rounded-full border-2 border-blue-400 text-blue-700 bg-white font-semibold shadow hover:bg-yellow-100 transition">
      Xem thêm
    </button>
  </div>
</section>

<script>
  // Khi bấm "Xem thêm" sẽ hiện toàn bộ các div .hidden trong grid
  document.getElementById('showMoreBtn').addEventListener('click', function() {
    document.querySelectorAll('#productGrid > .hidden').forEach(el => el.classList.remove('hidden'));
    this.style.display = 'none'; // Ẩn nút "Xem thêm"
  });
</script>
    
  </main>

<!-- Tin tức nổi bật -->
<section class="max-w-6xl mx-auto px-4 py-10">
  <h2 class="text-2xl md:text-3xl font-semibold text-blue-600 mb-8 text-center tracking-wide uppercase">
    Tin tức nổi bật
  </h2>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    <!-- Tin 1 -->
    <div class="bg-white rounded-lg shadow hover:shadow-lg transition p-0">
      <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=600&q=80"
           alt="Chế biến cà phê" class="w-full h-48 object-cover rounded-t-lg">
      <div class="p-4">
        <h3 class="font-bold text-gray-800 uppercase text-base mb-2">Chế biến cà phê</h3>
        <p class="text-gray-600 text-sm">
          Cà phê sạch luôn phải giữ hương vị 100% cà phê, không pha thêm tạp chất và giữ được hương vị đặc trưng...
        </p>
      </div>
    </div>
    <!-- Tin 2 -->
    <div class="bg-white rounded-lg shadow hover:shadow-lg transition p-0">
      <img src="https://doctormuoi.vn/wp-content/uploads/2021/01/cac-loai-cafe-duoc-yeu-thich.jpg"
           alt="COFFEE THẾ KỶ" class="w-full h-48 object-cover rounded-t-lg">
      <div class="p-4">
        <h3 class="font-bold text-gray-800 uppercase text-base mb-2">Coffee thế kỷ</h3>
        <p class="text-gray-600 text-sm">
          Cà phê là hưởng thụ một phong cách pha chế và quản lý, mang lại nhiều trải nghiệm thú vị...
        </p>
      </div>
    </div>
    <!-- Tin 3 -->
    <div class="bg-white rounded-lg shadow hover:shadow-lg transition p-0">
      <img src="https://images.unsplash.com/photo-1511920170033-f8396924c348?auto=format&fit=crop&w=600&q=80"
           alt="Đẳng cấp qua cốc cà phê" class="w-full h-48 object-cover rounded-t-lg">
      <div class="p-4">
        <h3 class="font-bold text-gray-800 uppercase text-base mb-2">Đẳng cấp qua cốc cà phê</h3>
        <p class="text-gray-600 text-sm">
          Uống cà phê sành, thể hiện phong cách riêng biệt, thưởng thức trọn vẹn vị ngon của từng giọt cà phê...
        </p>
      </div>
    </div>
  </div>
</section>

  <!-- Placeholder cho footer -->
  <div id="footer"></div>

  <!-- JavaScript để tải header và footer -->
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
</body>
</html>
