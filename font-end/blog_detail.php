<?php
session_start();
include 'db.php';

$blog_id = $_GET['id'] ?? 0;

$stmt = $conn->prepare("SELECT * FROM blogs WHERE blog_id = ?");
$stmt->bind_param("i", $blog_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<h2 style='text-align:center; color:red;'>Bài viết không tồn tại.</h2>";
    exit;
}

$blog = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= htmlspecialchars($blog['title']) ?> | MT Store</title>
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

        .header .icons .fas {
            font-size: 1.5rem;
            margin-left: 15px;
            cursor: pointer;
        }

        .header .icons .fas:hover {
            color: #3bb77e;
        }

        .search-bar {
            text-align: center;
            margin: 20px;
        }

        .search-bar input {
            padding: 10px;
            width: 300px;
            font-size: 1rem;
            border-radius: .5rem;
        }

        .search-bar button {
            padding: 10px 20px;
            background: #3bb77e;
            border-radius: .5rem;
            color: white;
            border: none;
            font-size: 1rem;
            cursor: pointer;
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-top: 0;
            color: #333;
        }

        .meta {
            color: #777;
            margin-bottom: 20px;
        }

        img.blog-image {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .content {
            font-size: 1.1rem;
            line-height: 1.6;
            color: #444;
        }

        .back-link {
            display: inline-block;
            margin-top: 30px;
            color: #3bb77e;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
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
            <a href="#about">About</a>
            <a href="#product">Product</a>
            <a href="#review">Review</a>
            <a href="#blog">Blog</a>
            <a href="#contact">Contact</a>
        </nav>
        <div class="icons">
            <div id="cart-btn" class="fas fa-shopping-basket" onclick="window.location.href='cart.php'"></div>
            <div id="login-btn" class="fas fa-user"></div>
            <div id="sign-out" class="fas fa-sign-out-alt"></div>
        </div>
        <script>
            document.getElementById("sign-out").addEventListener("click", function() {
                window.location.href = "logout.php";
            });
            document.getElementById("login-btn").addEventListener("click", function() {
                window.location.href = "login.php";
            });
        </script>
    </header>

    <div class="container">
        <h2><?= htmlspecialchars($blog['title']) ?></h2>
        <div class="meta">Tác giả: <?= htmlspecialchars($blog['author']) ?></div>
        <img src="<?= htmlspecialchars($blog['image']) ?>" alt="Ảnh blog" class="blog-image">
        <div class="content">
            <?= nl2br(htmlspecialchars($blog['content'])) ?>
        </div>
        <a href="blog.php" class="back-link">← Quay về trang Blog</a>
    </div>


    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</body>

</html>