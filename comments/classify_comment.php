<?php
session_start();
require_once '../manage/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'], $_SESSION['user_id'])) {
    $comment = trim($_POST['comment']);
    $product_id = intval($_POST['product_id']);
    $user_id = $_SESSION['user_id'];

    $escaped_comment = escapeshellarg($comment);
    $script_path = escapeshellarg(__DIR__ . "/predict.py");
    $escaped_comment = escapeshellarg($comment);
    $cmd = "set PYTHONIOENCODING=utf-8 && python $script_path $escaped_comment 2>&1";
    $output = shell_exec($cmd);

    $lines = explode("\n", trim($output));
    $last_line = end($lines);
    $support = (int)trim($last_line);

    echo "<pre>";
    echo "CMD: $cmd\n";
    echo "Output:\n$output\n";
    echo "Last Line: $last_line\n";
    echo "Parsed support: $support\n";
    echo "</pre>";

    $stmt = $conn->prepare("INSERT INTO comments (product_id, user_id, content, support) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iisi", $product_id, $user_id, $comment, $support);
    $stmt->execute();

    header("Location: ../home/product_detail.php?id=$product_id");
    exit;
} else {
    echo "<pre>❌ Không thể xử lý bình luận vì:\n- Không phải POST request\n- Hoặc thiếu dữ liệu</pre>";
    echo "<pre>POST: "; print_r($_POST); echo "</pre>";
}
