<?php
session_start();
include 'db.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['is_admin'] = $user['is_admin'];
            header("Location: index.php");
            exit;
        } else {
            $error = "Sai mật khẩu.";
        }
    } else {
        $error = "Email không tồn tại.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            margin: 0;
            padding: 0;
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
            color: rgb(0, 0, 0);
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

        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 45rem;
        }

        .form-box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 30rem;
            display: none;
        }

        .form-box.active {
            display: block;
        }

        .form-box h3 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-box input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            font-size: 1rem;
            border-radius: .7rem;
        }

        .form-box button {
            width: 100%;
            padding: 10px;
            background: #3bb77e;
            color: white;
            font-size: 1rem;
            border: none;
            border-radius: .7rem;
            cursor: pointer;
        }

        .form-box .link {
            text-align: center;
            margin-top: 10px;
        }

        .form-box .link a {
            text-decoration: underline;
            color: #3bb77e;
            cursor: pointer;
        }

        .error {
            color: red;
            text-align: center;
            margin-top: 10px;
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
                        $cart_count += $item['quantity'] ?? 1;
                    }
                }
                if ($cart_count > 0):
                ?>
                    <span class="cart-badge"><?= $cart_count ?></span>
                <?php endif; ?>
            </div>
            <div id="login-btn" class="fas fa-user"></div>
            <div id="sign-out" class="fas fa-sign-out-alt" onclick="window.location.href='logout.php'"></div>
        </div>
    </header>

    <div class="login-container">
        <!-- Form Login -->
        <form method="post" class="form-box active" id="loginForm">
            <h3>LOGIN</h3>
            <?php if ($error): ?><p class="error"><?= $error ?></p><?php endif; ?>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">LOGIN</button>
            <div class="link">Bạn chưa có tài khoản? <a onclick="switchForm('registerForm')">Đăng ký</a></div>
        </form>

        <!-- Form Register -->
        <form method="post" action="register_process.php" class="form-box" id="registerForm">
            <h3>REGISTER</h3>
            <input type="text" name="full_name" placeholder="Họ tên" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Mật khẩu" required>
            <button type="submit">ĐĂNG KÝ</button>
            <div class="link">Đã có tài khoản? <a onclick="switchForm('loginForm')">Đăng nhập</a></div>
        </form>
    </div>

    <script>
        function switchForm(formId) {
            document.getElementById('loginForm').classList.remove('active');
            document.getElementById('registerForm').classList.remove('active');
            document.getElementById(formId).classList.add('active');
        }
    </script>
</body>

</html>