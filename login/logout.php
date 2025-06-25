<?php
session_start();
session_unset(); // Xoá toàn bộ biến session
session_destroy(); // Huỷ session
header("Location: ../home/index.php"); // Quay về trang chủ (có thể chỉnh lại nếu file khác)
exit;
