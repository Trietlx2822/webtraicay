<?php
include 'db.php';
session_start();

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$product_id) {
    echo "Sản phẩm không tồn tại.";
    exit;
}

$stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    echo "Không tìm thấy sản phẩm.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= htmlspecialchars($product['name']) ?> | MT Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f9f9f9;
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

        .header .icons .fas {
            font-size: 1.5rem;
            margin-left: 15px;
            cursor: pointer;
        }

        .header .icons .fas:hover {
            color: #3bb77e;
        }

        .container {
            max-width: 1000px;
            margin: 30px auto;
            background: #fff;
            display: flex;
            gap: 30px;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .left,
        .right {
            flex: 1;
        }

        .left img {
            width: 100%;
            border-radius: 10px;
        }

        .right h2 {
            font-size: 2rem;
        }

        .price {
            color: red;
            font-size: 1.6rem;
            margin: 10px 0;
        }

        .desc {
            margin: 15px 0;
            color: #444;
        }

        .qty {
            margin: 20px 0;
        }

        input[type=number] {
            width: 60px;
            padding: 6px;
            font-size: 1rem;
        }

        button {
            background: #3bb77e;
            color: white;
            padding: 10px 20px;
            font-size: 1rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background: #2e9b65;
        }

        #toast {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #3bb77e;
            color: white;
            padding: 12px 25px;
            border-radius: 5px;
            display: none;
            z-index: 9999;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
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
            <div id="login-btn" class="fas fa-user" onclick="window.location.href='login.php'"></div>
            <div id="sign-out" class="fas fa-sign-out-alt" onclick="window.location.href='logout.php'"></div>
        </div>
    </header>


    <div class="container">
        <div class="left">
            <img src="<?= $product['image'] ?>" alt="<?= $product['name'] ?>">
        </div>
        <div class="right">
            <h2><?= htmlspecialchars($product['name']) ?></h2>
            <p class="price">$<?= number_format($product['price'], 2) ?></p>
            <p class="desc">
                <?= htmlspecialchars($product['description'] ?? 'Sản phẩm chất lượng cao, phù hợp mọi nhu cầu.') ?></p>

            <form id="add-cart-form">
                <input type="hidden" name="id" value="<?= $product['product_id'] ?>">
                <div class="qty">
                    <label>Số lượng:</label>
                    <input type="number" name="quantity" id="quantity" value="1" min="1">
                </div>
                <button type="submit">Thêm vào giỏ hàng</button>
            </form>
        </div>
    </div>


    <div id="toast"></div>

    <script>
        function showToast(message) {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.style.display = 'block';
            setTimeout(() => toast.style.display = 'none', 2000);
        }

        document.getElementById('add-cart-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const id = this.querySelector('input[name="id"]').value;
            const quantity = document.getElementById('quantity').value;

            fetch('add_to_cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `id=${id}&quantity=${quantity}`
                })
                .then(res => res.text())
                .then(data => {
                    showToast('Đã thêm vào giỏ hàng!');
                })
                .catch(err => {
                    showToast('Lỗi khi thêm vào giỏ hàng!');
                });
        });
    </script>

</body>

</html>