<?php
session_start();
include 'db.php';

// Xử lý xóa sản phẩm khỏi giỏ hàng
if (isset($_GET['remove'])) {
    $id = $_GET['remove'];
    unset($_SESSION['cart'][$id]);
    header('Location: cart.php');
    exit;
}

// Xử lý cập nhật số lượng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_qty'])) {
    foreach ($_POST['qty'] as $product_id => $qty) {
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] = max(1, (int)$qty);
        }
    }
    header('Location: cart.php');
    exit;
}

// Lấy tên người dùng từ cơ sở dữ liệu
$user_name = '';
if (isset($_SESSION['user_id'])) {
    $uid = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT full_name FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $result_user = $stmt->get_result();
    if ($row_user = $result_user->fetch_assoc()) {
        $user_name = $row_user['full_name'];
    }
}

// Lấy thông tin sản phẩm trong giỏ
$cart_items = [];
$total = 0;
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $ids = implode(',', array_keys($_SESSION['cart']));
    $result = $conn->query("SELECT * FROM products WHERE product_id IN ($ids)");
    while ($row = $result->fetch_assoc()) {
        $id = $row['product_id'];
        $qty = $_SESSION['cart'][$id]['quantity'];
        $row['quantity'] = $qty;
        $row['subtotal'] = $row['price'] * $qty;
        $total += $row['subtotal'];
        $cart_items[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Giỏ hàng</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
        }

        .header {
            width: 100%;
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
            color: #000;
            font-weight: bold;
            text-decoration: none;
        }

        .header .logo i {
            color: #ffba43;
        }

        .header .navbar a {
            margin: 0 10px;
            color: #333;
            text-decoration: none;
            font-size: 1.1rem;
        }

        .header .navbar a:hover {
            color: #3bb77e;
        }

        .header .icons {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header .icons span {
            font-weight: bold;
            font-size: 1rem;
        }

        .header .icons .fas {
            font-size: 1.5rem;
            cursor: pointer;
        }

        .header .icons .fas:hover {
            color: #3bb77e;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #f0f0f0;
        }

        .total {
            text-align: right;
            font-size: 1.2rem;
            margin-top: 20px;
        }

        .actions button,
        .actions a {
            background: #3bb77e;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            border-radius: .5rem;
        }

        .actions a {
            background: #e74c3c;
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
            <div id="sign-out" class="fas fa-sign-out-alt"></div>
            <script>
                document.getElementById("sign-out").addEventListener("click", function() {
                    window.location.href = "logout.php";
                });
            </script>
        </div>
    </header>
    <h2>Giỏ hàng của <span><?= htmlspecialchars($user_name) ?></span>
        <!--tên người dùng -->
    </h2>
    <?php if (empty($cart_items)): ?>
        <p>Chưa có sản phẩm nào trong giỏ hàng.</p>
    <?php else: ?>
        <form method="post">
            <table>
                <thead>
                    <tr>
                        <th>Hình</th>
                        <th>Tên sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td><img src="<?= $item['image'] ?>" width="50"></td>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td>$<?= number_format($item['price'], 2) ?></td>
                            <td><input type="number" name="qty[<?= $item['product_id'] ?>]" value="<?= $item['quantity'] ?>"
                                    min="1"></td>
                            <td>$<?= number_format($item['subtotal'], 2) ?></td>
                            <td class="actions">
                                <a href="cart.php?remove=<?= $item['product_id'] ?>">Xoá</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="total">
                <strong>Tổng cộng: $<?= number_format($total, 2) ?></strong>
            </div>
            <div class="actions" style="margin-top: 20px;">
                <button type="submit" name="update_qty" style="border-radius: .5rem;">Cập nhật giỏ hàng</button>
                <a href="checkout.php" style="border-radius: .5rem;">Thanh toán</a>
            </div>
        </form>
    <?php endif; ?>
</body>

</html>