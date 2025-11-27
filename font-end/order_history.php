<?php
session_start();
include 'db.php';

// Phải đăng nhập mới xem được
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['user_id'];

// XỬ LÝ KHI KHÁCH BẤM "ĐÃ NHẬN ĐƯỢC HÀNG"
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_received'])) {
    $order_id = (int)$_POST['order_id'];

    $stmt = $conn->prepare("UPDATE orders SET status = 'Hoàn thành' 
                            WHERE order_id = ? AND user_id = ? AND status != 'Hoàn thành'");
    $stmt->bind_param("ii", $order_id, $user_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "<script>alert('Cảm ơn bạn! Đơn hàng đã được xác nhận hoàn thành!'); location.reload();</script>";
    }
    $stmt->close();
}

// LẤY DANH SÁCH ĐƠN HÀNG
$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch sử đơn hàng | MT Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f8f8;
            margin: 0;
        }

        .header {
            background: #fff;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #eee;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .header .logo {
            font-size: 2rem;
            font-weight: bold;
            color: #000;
            text-decoration: none;
        }

        .header .logo i {
            color: #ffba43;
        }

        .navbar a {
            margin: 0 12px;
            color: #333;
            text-decoration: none;
            font-size: 1.1rem;
        }

        .navbar a:hover {
            color: #3bb77e;
        }

        .icons .fas {
            font-size: 1.5rem;
            margin-left: 15px;
            cursor: pointer;
        }

        .icons .fas:hover {
            color: #3bb77e;
        }

        .container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #3bb77e;
            margin-bottom: 30px;
        }

        .order-card {
            border: 1px solid #eee;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            background: #fdfdfd;
            transition: 0.3s;
        }

        .order-card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .order-card h3 {
            margin: 0 0 10px 0;
            color: #333;
        }

        .order-card p {
            margin: 8px 0;
            color: #555;
        }

        .status {
            padding: 6px 14px;
            border-radius: 20px;
            color: #fff;
            font-size: 0.9rem;
            font-weight: bold;
        }

        .status.pending {
            background: #e67e22;
        }

        /* Đang xử lý */
        .status.shipping {
            background: #3498db;
        }

        /* Đang giao */
        .status.completed {
            background: #27ae60;
        }

        /* Hoàn thành */
        .status.cancelled {
            background: #e74c3c;
        }

        .btn-confirm {
            background: #27ae60;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 0.95rem;
            margin-top: 10px;
        }

        .btn-confirm:hover {
            background: #219653;
        }

        .confirmed-text {
            color: #27ae60;
            font-weight: bold;
            margin-top: 10px;
            font-size: 0.95rem;
        }

        .no-orders {
            text-align: center;
            padding: 50px;
            color: #777;
            font-size: 1.2rem;
        }

        .back-home a {
            display: inline-block;
            background: #3bb77e;
            color: #fff;
            padding: 12px 30px;
            border-radius: 50px;
            text-decoration: none;
            margin-top: 20px;
        }

        .back-home a:hover {
            background: #2c9e6c;
        }

        .cart-icon {
            position: relative;
            display: inline-block;
            cursor: pointer;
            font-size: 1.7rem;
            color: #333;
            padding: 8px;
            transition: 0.3s;
        }

        .cart-icon:hover {
            color: #3bb77e;
        }

        .cart-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #e74c3c;
            color: white;
            font-size: 0.75rem;
            font-weight: bold;
            min-width: 18px;
            height: 18px;
            line-height: 18px;
            text-align: center;
            border-radius: 50%;
            border: 2px solid #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>

<body>

    <header class="header">
        <a href="index.php" class="logo"><i class="fas fa-shopping-cart"></i> MT Store</a>
        <nav class="navbar">
            <a href="index.php">Home</a>
            <a href="about.php">About</a>
            <a href="product.php">Product</a>
            <a href="blog.php">Blog</a>
            <a href="review.php">Review</a>
            <a href="order_history.php">Đơn hàng</a>
        </nav>
        <div class="icons">
            <div class="cart-icon" onclick="location.href='cart.php'">
                <i class="fas fa-shopping-basket"></i>
                <?php
                $cart_count = 0;
                if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
                    foreach ($_SESSION['cart'] as $item) {
                        $cart_count += $item['quantity'];
                    }
                }
                if ($cart_count > 0):
                ?>
                    <span class="cart-badge"><?= $cart_count ?></span>
                <?php endif; ?>
            </div>
            <div class="fas fa-user" onclick="location.href='login.php'"></div>
            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="fas fa-sign-out-alt" onclick="location.href='logout.php'"></div>
            <?php endif; ?>
        </div>
    </header>

    <div class="container">
        <h2>LỊCH SỬ ĐƠN HÀNG</h2>

        <?php if ($orders->num_rows > 0): ?>
            <?php while ($order = $orders->fetch_assoc()): ?>
                <?php
                $status = $order['status'];
                $status_class = 'pending';
                if ($status == 'Đang giao') $status_class = 'shipping';
                elseif ($status == 'Hoàn thành') $status_class = 'completed';
                elseif ($status == 'Đã hủy') $status_class = 'cancelled';
                ?>
                <div class="order-card">
                    <h3>Đơn hàng #<?= $order['order_id'] ?> - <?= date('d/m/Y H:i', strtotime($order['order_date'])) ?></h3>
                    <p><strong>Tổng tiền:</strong> <span
                            style="color:#e67e22; font-weight:bold;">$<?= number_format($order['total_amount'], 2) ?></span></p>
                    <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($order['address']) ?></p>
                    <p><strong>Phương thức:</strong>
                        <?= $order['payment_method'] == 'cash' ? 'Thanh toán khi nhận hàng' : 'Chuyển khoản' ?></p>
                    <p><strong>Trạng thái:</strong>
                        <span class="status <?= $status_class ?>"><?= $status ?></span>
                    </p>

                    <!-- NÚT XÁC NHẬN ĐÃ NHẬN HÀNG -->
                    <?php if ($status !== 'Hoàn thành' && $status !== 'Đã hủy'): ?>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                            <button type="submit" name="confirm_received" class="btn-confirm"
                                onclick="return confirm('Bạn đã nhận được hàng và muốn xác nhận hoàn tất đơn hàng này?')">
                                Đã nhận được hàng
                            </button>
                        </form>
                    <?php elseif ($status === 'Hoàn thành'): ?>
                        <div class="confirmed-text">Đã xác nhận nhận hàng</div>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-orders">
                <p>Chưa có đơn hàng nào.</p>
                <p>Hãy mua sắm ngay để trải nghiệm dịch vụ của chúng tôi nào!</p>
            </div>
        <?php endif; ?>

        <div class="back-home" style="text-align:center;">
            <a href="product.php">Tiếp tục mua sắm</a>
        </div>
    </div>

</body>

</html>