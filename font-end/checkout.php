<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Lấy thông tin người dùng
$uid = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT full_name, email FROM users WHERE user_id = ?");
$stmt->bind_param("i", $uid);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Lấy sản phẩm trong giỏ hàng
$cart_items = [];
$total = 0;
if (!empty($_SESSION['cart'])) {
    $ids = implode(",", array_keys($_SESSION['cart']));
    $result = $conn->query("SELECT * FROM products WHERE product_id IN ($ids)");
    while ($row = $result->fetch_assoc()) {
        $id = $row['product_id'];
        $qty = $_SESSION['cart'][$id]['quantity'];
        $row['quantity'] = $qty;
        $row['subtotal'] = $row['price'] * $qty;
        $cart_items[] = $row;
        $total += $row['subtotal'];
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Thanh toán | MT Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial;
            margin: 0;
            padding: 0;
            background: #f6f6f6;
        }

        .header {
            background: #fff;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #eee;
        }

        .logo {
            font-size: 2rem;
            font-weight: bold;
            color: #000;
            text-decoration: none;
        }

        .logo i {
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
            margin-left: 15px;
            font-size: 1.5rem;
            cursor: pointer;
        }

        .icons .fas:hover {
            color: #3bb77e;
        }

        .icons span {
            font-weight: bold;
            margin-left: 10px;
        }

        .container {
            max-width: 1000px;
            margin: 30px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }

        th {
            background: #f0f0f0;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-section h3 {
            margin-bottom: 10px;
            color: #3bb77e;
        }

        .form-actions {
            display: flex;
            justify-content: space-between;
        }

        .form-actions button {
            padding: 12px 25px;
            font-size: 1rem;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .form-actions .back {
            background: #ccc;
        }

        .form-actions .submit {
            background: #3bb77e;
            color: white;
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
            <a href="review.php">Review</a>
            <a href="blog.php">Blog</a>
            <a href="index.php">Contact</a>
        </nav>
        <div class="icons">

            <span><?= htmlspecialchars($user['full_name']) ?></span>

            <div class="fas fa-sign-out-alt" onclick="location.href='logout.php'"></div>
        </div>
    </header>

    <div class="container">
        <h2>Thông tin đơn hàng</h2>

        <form action="process_order.php" method="POST">
            <table>
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Đơn giá</th>
                        <th>Số lượng</th>
                        <th>Số tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td>$<?= number_format($item['price'], 2) ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td>$<?= number_format($item['subtotal'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="3" style="text-align:right;"><strong>Tổng cộng:</strong></td>
                        <td><strong>$<?= number_format($total, 2) ?></strong></td>
                    </tr>
                </tbody>
            </table>

            <div class="form-section">
                <h3>Thông tin giao hàng</h3>
                <label>Họ tên:</label>
                <input type="text" name="name" value="<?= htmlspecialchars($user['full_name']) ?>" required>

                <label>Email:</label>
                <input type="text" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

                <label>Số điện thoại:</label>
                <input type="tel" name="phone" placeholder="Nhập số điện thoại..." required>

                <label>Địa chỉ giao hàng:</label>
                <textarea name="address" placeholder="Nhập địa chỉ cụ thể hoặc dùng định vị..." required></textarea>

                <label>Phương thức thanh toán:</label>
                <select name="payment_method" required>
                    <option value="cash">Thanh toán khi nhận hàng (COD)</option>
                    <option value="bank">Chuyển khoản</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="button" class="back" onclick="window.location.href='cart.php'">Quay về giỏ hàng</button>
                <button type="submit" class="submit">Xác nhận đặt hàng</button>
            </div>
        </form>
    </div>
</body>

</html>