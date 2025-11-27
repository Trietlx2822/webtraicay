<?php
session_start();
include 'db.php';

$result = $conn->query("SELECT * FROM blogs ORDER BY blog_id DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Blog | MT Store</title>
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

        .blog-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        h2.title {
            text-align: center;
            color: #3bb77e;
            margin-bottom: 40px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .blog-card {
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s;
        }

        .blog-card:hover {
            transform: translateY(-5px);
        }

        .blog-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .blog-content {
            padding: 15px;
        }

        .blog-content h3 {
            margin: 0 0 10px;
        }

        .blog-content p {
            margin: 0;
            color: #555;
        }

        .blog-content .author {
            font-size: 0.9rem;
            color: #888;
            margin-bottom: 8px;
        }

        .blog-content a {
            display: inline-block;
            margin-top: 10px;
            color: #3bb77e;
            text-decoration: none;
        }

        .blog-content a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .blog-card img {
                height: 160px;
            }
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

    <div class="blog-container">
        <h2 class="title">OUR BLOG</h2>
        <div class="grid">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="blog-card">
                    <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['title']) ?>">
                    <div class="blog-content">
                        <h3><?= htmlspecialchars($row['title']) ?></h3>
                        <div class="author">By <?= htmlspecialchars($row['author']) ?></div>
                        <p><?= substr(strip_tags($row['content']), 0, 100) ?>...</p>
                        <a href="blog_detail.php?id=<?= $row['blog_id'] ?>">Đọc thêm</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>


    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</body>

</html>