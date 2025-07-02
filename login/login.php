<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once '../manage/database.php';


$thongbao = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $en_password = MD5($password);
    // Truy vấn thông tin tài khoản
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // So sánh password (nếu đã dùng password_hash)
        if ($en_password === $user['password']){
            $_SESSION['username'] = $user['username'];
            $_SESSION['ho_ten'] = $user['ho_ten'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['user_id'] = $user['id'];

            echo "đăng nhập thành công";
            header("Location: ../cart/init_cart.php");
            exit;
        } else {
            $thongbao = "Sai mật khẩu!";
        }
    } else {
        $thongbao = "Tài khoản không tồn tại!";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập | drinkMART</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="../style.css" />
</head>
<body class="flex flex-col min-h-screen bg-gradient-to-b from-blue-50 to-blue-100">

    <!-- Kế thừa header -->
    <div id="header"></div>

    <!-- Nội dung login -->
    <main class="flex-grow flex items-center justify-center py-10">
        <div class="login-container bg-white p-8 rounded-2xl shadow-xl max-w-md w-full">
            <div class="logo text-center mb-6">
                <h1 class="text-3xl font-bold text-blue-500 mb-2">Đăng Nhập</h1>
                <p class="text-gray-500">Vui lòng nhập thông tin để tiếp tục</p>
            </div>
            <form method="POST" action ="" id="loginForm">
                <div class="mb-5">
                    <label for="email" class="block mb-2 text-gray-700 font-semibold">Tên Đăng Nhập</label>
                    <input type="username" id="username" name="username" required placeholder="Tên đăng nhập"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 transition">
                </div>
                <div class="mb-5">
                    <label for="password" class="block mb-2 text-gray-700 font-semibold">Mật khẩu</label>
                    <input type="password" id="password" name="password" required placeholder="Nhập mật khẩu"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 transition">
                </div>
                <div class="flex items-center mb-5">
                    <input type="checkbox" id="remember" name="remember" class="mr-2">
                    <label for="remember" class="text-gray-600 text-sm">Ghi nhớ đăng nhập</label>
                </div>
                <button type="submit"
                    class="login-btn w-full py-3 bg-blue-500 hover:bg-blue-600 text-white font-bold rounded-lg transition mb-4">Đăng Nhập</button>
                <div class="text-center mb-3">
                    <a href="#" onclick="showForgotPassword()" class="text-blue-500 hover:underline text-sm">Quên mật khẩu?</a>
                </div>
                <div class="text-center border-t pt-4">
                    <span class="text-gray-600 text-sm">Chưa có tài khoản? </span>
                    <a href="../register/register.php" class="text-blue-500 font-bold hover:underline text-sm">Đăng ký ngay</a>
                </div>
            </form>
            <?php if ($thongbao != '') echo "<p style='color:red;'>$thongbao</p>"; ?>
        </div>
    </main>

    <!-- Kế thừa footer -->
    <div id="footer"></div>

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
</body>
</html>
