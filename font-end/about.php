<?php
session_start();
include 'db.php';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>About Us | MT Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f8f8f8;
        }

        .header {
            background: #fff;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #eee;
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
            font-size: 1.4rem;
            margin-left: 15px;
            cursor: pointer;
        }

        .icons span {
            margin-left: 10px;
            font-weight: bold;
        }

        .container {
            max-width: 1000px;
            margin: 40px auto;
            background: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.08);
        }

        .container h2 {
            text-align: center;
            color: #3bb77e;
            margin-bottom: 30px;
        }

        .container p {
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 20px;
            color: #444;
        }

        .container img {
            max-width: 100%;
            display: block;
            margin: 20px auto;
            border-radius: 10px;
        }

        .back-btn {
            display: block;
            text-align: center;
            margin-top: 40px;
        }

        .back-btn a {
            padding: 10px 20px;
            background: #3bb77e;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
        }

        .back-btn a:hover {
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
            <a href="about.php" style="color: #3bb77e; font-weight: bold;">About</a>
            <a href="product.php">Product</a>
            <a href="blog.php">Blog</a>
            <a href="index.php">Contact</a>
            <a href="review.php">Review</a>
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
            <div class="fas fa-user" onclick="window.location.href='login.php'"></div>
        </div>
    </header>

    <div class="container">
        <h2>About MT Store</h2>
        <p>
            At MT Store, we are dedicated to providing the freshest, healthiest, and most sustainable organic foods to
            your family’s table.
            Our vision is rooted in a belief that healthy food should be accessible, affordable, and safe for everyone.
            That's why we carefully source our products from trusted organic farms that use environmentally friendly and
            chemical-free practices.
        </p>
        <p>
            Every fruit, vegetable, and product we offer is grown with care, harvested with love, and delivered with
            responsibility. We believe that food is not just something to fill your stomach—it’s something that should
            nourish your body, mind, and soul.
        </p>
        <p>
            We also support local farmers and fair trade, ensuring that our business helps grow the community and
            protects the planet. By choosing MT Store, you’re not just buying food—you’re joining a movement for better
            health and a cleaner environment.
        </p>
        <img src="../font-end/images/about1.png" alt="Organic Food Banner">

        <div class="back-btn">
            <a href="index.php">← Quay lại trang chủ</a>
        </div>
    </div>
</body>

</html>