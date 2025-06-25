<?php
session_start();
require_once '../manage/database.php';
if (!$conn) {
    die("Kết nối CSDL thất bại: " . mysqli_connect_error());
}
$thongbao = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $email = trim($_POST["email"]);
    $ho_ten = trim($_POST["ho_ten"]);

    // Kiểm tra xác nhận mật khẩu
    if ($password !== $confirm_password) {
        $thongbao = "Mật khẩu xác nhận không khớp!";
    } else {
        // Kiểm tra trùng username hoặc email
        $sql_check = "SELECT id FROM users WHERE username = ? OR email = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("ss", $username, $email);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $thongbao = "Tên đăng nhập hoặc email đã tồn tại!";
        } else {
            // Mã hóa mật khẩu (nếu dùng password_hash)
            // $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $hashed_password = $password; // Đơn giản hóa (có thể thay bằng hash sau)

            $sql_insert = "INSERT INTO users (username, password, email, ho_ten) VALUES (?, ?, ?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bind_param("ssss", $username, $hashed_password, $email, $ho_ten);

if ($stmt_insert->execute()) {
    header("Location: ../login/login.php");
    exit;
} else {
    $thongbao = "Đăng ký thất bại: " . $conn->error;
}
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Đăng Ký | drinkMART</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="../style.css" />
</head>
<body class="flex flex-col min-h-screen bg-gradient-to-b from-blue-50 to-blue-100">

  <!-- Kế thừa header -->
  <div id="header"></div>

  <!-- Nội dung register -->
  <main class="flex-grow flex items-center justify-center py-10">
    <div class="login-container bg-white p-8 rounded-2xl shadow-xl max-w-md w-full">
      <div class="logo text-center mb-6">
        <h1 class="text-3xl font-bold text-blue-500 mb-2">Đăng Ký</h1>
        <p class="text-gray-500">Vui lòng nhập thông tin để tạo tài khoản</p>
      </div>
      <form method="POST" action="" id="registerForm">
        <div class="mb-5">
          <label for="username" class="block mb-2 text-gray-700 font-semibold">Tên Đăng Nhập</label>
          <input type="text" id="username" name="username" required placeholder="Tên Đăng Nhập"
            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 transition">
        </div>
        <div class="mb-5">
          <label for="name" class="block mb-2 text-gray-700 font-semibold">Họ và tên</label>
          <input type="text" id="name" name="ho_ten" required placeholder="Nhập họ tên"
            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 transition">
        </div>
        <div class="mb-5">
          <label for="email" class="block mb-2 text-gray-700 font-semibold">Email</label>
          <input type="email" id="email" name="email" required placeholder="Nhập email"
            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 transition">
        </div>
        <div class="mb-5">
          <label for="password" class="block mb-2 text-gray-700 font-semibold">Mật khẩu</label>
          <input type="password" id="password" name="password" required placeholder="Tạo mật khẩu"
            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 transition">
        </div>
        <div class="mb-5">
          <label for="confirm" class="block mb-2 text-gray-700 font-semibold">Xác nhận mật khẩu</label>
          <input type="password" id="confirm_password" name="confirm_password" required placeholder="Nhập lại mật khẩu"
            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 transition">
        </div>
        <?php if (!empty($thongbao)) : ?>
  <p class="text-red-500 text-sm mb-4 font-semibold"><?php echo $thongbao; ?></p>
<?php endif; ?>
        <button type="submit"
          class="login-btn w-full py-3 bg-blue-500 hover:bg-blue-600 text-white font-bold rounded-lg transition mb-4">Đăng Ký</button>
        
        <!-- Liên kết điều hướng -->
        <div class="text-center border-t pt-4 space-y-2">
          <div>
            <span class="text-gray-600 text-sm">Đã có tài khoản? </span>
            <a href="../login/login.php" class="text-blue-500 font-bold hover:underline text-sm">Đăng nhập</a>
          </div>
        </div>
      </form>
    </div>
  </main>

  <!-- Footer -->
  <div id="footer"></div>

  
</body>
</html>
