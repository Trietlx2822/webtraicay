<?php
session_start();
include 'db.php';

// Kiểm tra người dùng đăng nhập
if (!isset($_SESSION['user_id']) || empty($_SESSION['cart'])) {
    header('Location: login.php');
    exit;
}

// Lấy thông tin người dùng và giỏ hàng
$user_id = $_SESSION['user_id'];
$fullname = $_POST['name'] ?? '';
$phone = $_POST['phone'] ?? '';
$address = $_POST['address'] ?? '';
$payment_method = $_POST['payment_method'] ?? '';

// Kiểm tra dữ liệu bắt buộc
if (empty($fullname) || empty($phone) || empty($address)) {
    echo "Vui lòng điền đầy đủ thông tin giao hàng.";
    exit;
}

// Tính tổng tiền
$total_amount = 0;
foreach ($_SESSION['cart'] as $item) {
    $total_amount += $item['quantity'] * $item['price'];
}

// Thêm đơn hàng vào bảng `orders`
$stmt = $conn->prepare("INSERT INTO orders (user_id, order_date, total_amount, address, phone, payment_method, status) VALUES (?, NOW(), ?, ?, ?, ?, 'Đang xử lý')");
$stmt->bind_param("idsss", $user_id, $total_amount, $address, $phone, $payment_method);
$stmt->execute();
$order_id = $stmt->insert_id;

// Thêm từng sản phẩm vào bảng `order_details`
$stmt_detail = $conn->prepare("INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
foreach ($_SESSION['cart'] as $product_id => $item) {
    $quantity = $item['quantity'];
    $price = $item['price'];
    $stmt_detail->bind_param("iiid", $order_id, $product_id, $quantity, $price);
    $stmt_detail->execute();
}

// Xóa giỏ hàng
unset($_SESSION['cart']);

// Chuyển đến trang cảm ơn hoặc thông báo
echo "<script>
    alert('Đặt hàng thành công!');
    window.location.href = 'index.php';
</script>";
?>
