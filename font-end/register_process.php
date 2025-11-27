<?php
session_start();
include 'db.php';

// Lấy dữ liệu từ form
$full_name = $_POST['full_name'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// Kiểm tra rỗng
if (empty($full_name) || empty($email) || empty($password)) {
    echo "Vui lòng nhập đầy đủ thông tin.";
    exit;
}

// Kiểm tra email đã tồn tại chưa
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    echo "Email đã được sử dụng.";
    exit;
}

// Mã hoá mật khẩu
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Thêm người dùng mới
$stmt = $conn->prepare("INSERT INTO users (email, password, full_name, is_admin) VALUES (?, ?, ?, 0)");
$stmt->bind_param("sss", $email, $hashed_password, $full_name);

if ($stmt->execute()) {
    // Đăng nhập tự động sau khi đăng ký
    $_SESSION['user_id'] = $stmt->insert_id;
    $_SESSION['email'] = $email;
    $_SESSION['role'] = 'user';
    header("Location: index.php");
    exit;
} else {
    echo "Đăng ký thất bại. Vui lòng thử lại.";
}
?>
