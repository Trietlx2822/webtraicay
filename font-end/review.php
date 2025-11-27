<?php
include 'db.php';
session_start();

// Thêm review
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_review'])) {
    $user_name = $_POST['user_name'];
    $content = $_POST['content'];

    $stmt = $conn->prepare("INSERT INTO reviews (user_name, content) VALUES (?, ?)");
    $stmt->bind_param("ss", $user_name, $content);
    $stmt->execute();
    header("Location: review.php");
    exit;
}

// Xóa review
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM reviews WHERE id = $id");
    header("Location: review.php");
    exit;
}

// Sửa review
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_review'])) {
    $id = $_POST['id'];
    $user_name = $_POST['user_name'];
    $content = $_POST['content'];

    $stmt = $conn->prepare("UPDATE reviews SET user_name=?, content=? WHERE id=?");
    $stmt->bind_param("ssi", $user_name, $content, $id);
    $stmt->execute();
    header("Location: review.php");
    exit;
}

// Lấy danh sách review
$reviews = $conn->query("SELECT * FROM reviews ORDER BY created_at DESC");

// Lấy dữ liệu cần sửa nếu có
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $res = $conn->query("SELECT * FROM reviews WHERE id = $id");
    $edit_data = $res->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Review Sản Phẩm | MT Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f6f6f6;
            margin: 0;
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
            margin: 0 10px;
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

        .container {
            max-width: 800px;
            margin: 30px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
        }

        textarea,
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            padding: 10px 20px;
            background: #3bb77e;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .review-box {
            background: #f9f9f9;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .review-box h4 {
            margin: 0;
        }

        .review-box p {
            margin: 5px 0 0 0;
        }

        .review-box .actions {
            margin-top: 10px;
        }

        .review-box .actions a {
            margin-right: 10px;
            color: #007bff;
            text-decoration: none;
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
            <div class="fas fa-user" onclick="location.href='login.php'"></div>
            <div class="fas fa-sign-out-alt" onclick="location.href='logout.php'"></div>
        </div>
    </header>

    <div class="container">
        <h2><?= $edit_data ? 'Chỉnh sửa đánh giá' : 'Viết đánh giá' ?></h2>
        <form method="post">
            <?php if ($edit_data): ?>
                <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
                <input type="text" name="user_name" value="<?= htmlspecialchars($edit_data['user_name']) ?>" required>
                <textarea name="content" rows="4" required><?= htmlspecialchars($edit_data['content']) ?></textarea>
                <button type="submit" name="update_review">Cập nhật</button>
            <?php else: ?>
                <input type="text" name="user_name" placeholder="Tên của bạn" required>
                <textarea name="content" placeholder="Viết cảm nghĩ của bạn..." rows="4" required></textarea>
                <button type="submit" name="add_review">Đăng</button>
            <?php endif; ?>
        </form>

        <hr style="margin: 30px 0;">

        <h3>Đánh giá của người dùng</h3>
        <?php while ($review = $reviews->fetch_assoc()): ?>
            <div class="review-box">
                <h4><?= htmlspecialchars($review['user_name']) ?> <small
                        style="color:gray;">(<?= $review['created_at'] ?>)</small></h4>
                <p><?= nl2br(htmlspecialchars($review['content'])) ?></p>
                <div class="actions">
                    <a href="review.php?edit=<?= $review['id'] ?>">Sửa</a>
                    <a href="review.php?delete=<?= $review['id'] ?>"
                        onclick="return confirm('Bạn chắc chắn muốn xóa?')">Xóa</a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

</body>

</html>