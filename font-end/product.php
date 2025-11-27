<?php

$host = "localhost";
$user = "root";
$password = "";
$dbname = "store";

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$limit = 8;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

$category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';

//LẤY DANH MỤC
$category_result = $conn->query("SELECT * FROM categories ORDER BY name");

//XỬ LÝ TÌM KIẾM
$where_conditions = [];
$params = [];
$types = '';

if ($category_id > 0) {
    $where_conditions[] = "category_id = ?";
    $params[] = $category_id;
    $types .= 'i';
}

if (!empty($keyword)) {
    $words = array_filter(explode(' ', $keyword));
    foreach ($words as $word) {
        $where_conditions[] = "(name LIKE ? OR description LIKE ?)";
        $search = "%$word%";
        $params[] = $search;
        $params[] = $search;
        $types .= 'ss';
    }
}

$sql = "SELECT * FROM products";
if (!empty($where_conditions)) {
    $sql .= " WHERE " . implode(' AND ', $where_conditions);
}
$sql .= " ORDER BY product_id DESC LIMIT ? OFFSET ?";
$params[] = $limit;
$params[] = $offset;
$types .= 'ii';

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// === TÍNH TỔNG TRANG CHO PHÂN TRANG ===
$count_sql = "SELECT COUNT(*) as total FROM products";
if (!empty($where_conditions)) {
    $count_sql .= " WHERE " . implode(' AND ', $where_conditions);
}
$count_stmt = $conn->prepare($count_sql);
if (!empty($where_conditions)) {
    $count_types = substr($types, 0, -2); // bỏ 'ii' của limit/offset
    $count_params = array_slice($params, 0, -2);
    if (!empty($count_params)) {
        $count_stmt->bind_param($count_types, ...$count_params);
    }
}
$count_stmt->execute();
$total_rows = $count_stmt->get_result()->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>MT Store - Sản phẩm</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
        display: flex;
        width: 100%;
        padding: 30px;
        gap: 20px;
    }

    .sidebar {
        width: 250px;
        padding-right: 20px;
        border-right: 1px solid #ccc;
    }

    .sidebar h3 {
        font-size: 2rem;
        margin-bottom: 1rem;
    }

    .sidebar a {
        display: block;
        margin-bottom: 0.8rem;
        font-size: 1.6rem;
        color: #3bb77e;
        text-decoration: none;
    }

    .sidebar a:hover {
        text-decoration: underline;
    }

    .main-content {
        flex: 1;
        text-align: center;
    }

    .box-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 24px;
    }

    .box {
        background: #fff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        text-align: center;
        padding: 20px;
        transition: transform 0.3s;
    }

    .box:hover {
        transform: translateY(-5px);
    }

    .box img {
        width: 100%;
        height: 180px;
        object-fit: cover;
        border-radius: 8px;
    }

    .box h3 {
        margin-top: 15px;
        font-size: 1.5rem;
    }

    .box .price {
        font-size: 1.4rem;
        color: #3bb77e;
        margin: 10px 0;
    }

    .box .btn,
    .box button.add-to-cart {
        display: inline-block;
        padding: 10px 20px;
        background-color: #3bb77e;
        color: #fff;
        border-radius: 5px;
        text-decoration: none;
        font-size: 1rem;
        border: none;
        cursor: pointer;
    }

    .pagination {
        text-align: center;
        margin-top: 2rem;
    }

    .pagination a {
        margin: 0 5px;
        padding: 0.5rem 1rem;
        background: #3bb77e;
        color: white;
        text-decoration: none;
        border-radius: 5px;
    }

    #toast {
        position: fixed;
        top: 20px;
        right: 20px;
        background: #3bb77e;
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        display: none;
        z-index: 9999;
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
            <a href="contact.php">Contact</a>
            <?php if (isset($_SESSION['user_id'])): ?>
            <a href="order_history.php">Order History</a>
            <?php endif; ?>
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
            <?php if (isset($_SESSION['user_id'])): ?>
            <div class="fas fa-sign-out-alt" onclick="location.href='logout.php'" title="Đăng xuất"></div>
            <?php else: ?>
            <div class="fas fa-user" onclick="location.href='login.php'" title="Đăng nhập"></div>
            <?php endif; ?>
        </div>
    </header>

    <div class="search-bar">
        <form method="get">
            <input type="text" name="keyword" placeholder="Tìm kiếm sản phẩm..."
                value="<?= htmlspecialchars($keyword) ?>" required>
            <button type="submit">Tìm</button>
        </form>
    </div>

    <div class="container">
        <div class="sidebar">
            <h3>Danh Mục</h3>
            <a href="product.php" class="<?= ($category_id == 0) ? 'active' : '' ?>">Tất Cả</a>
            <?php
            if ($category_result && $category_result->num_rows > 0):
                $category_result->data_seek(0);
                while ($cat = $category_result->fetch_assoc()):
            ?>
            <a href="product.php?category_id=<?= $cat['category_id'] ?>&keyword=<?= urlencode($keyword) ?>"
                class="<?= ($category_id == $cat['category_id']) ? 'active' : '' ?>">
                <?= htmlspecialchars($cat['name']) ?>
            </a>
            <?php endwhile;
            endif; ?>
        </div>

        <div class="main-content">
            <h1 class="heading">SẢN <span style="color: orange">PHẨM</span></h1>

            <?php if ($result->num_rows > 0): ?>
            <div class="box-container">
                <?php while ($row = $result->fetch_assoc()): ?>
                <div class="box">
                    <a href="product_detail.php?id=<?= $row['product_id'] ?>">
                        <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                    </a>
                    <h3><?= htmlspecialchars($row['name']) ?></h3>
                    <div class="price">$<?= number_format($row['price'], 2) ?></div>
                    <button class="add-to-cart" data-id="<?= $row['product_id'] ?>">Add To Cart</button>
                </div>
                <?php endwhile; ?>
            </div>

            <!-- PHÂN TRANG -->
            <?php if ($total_pages > 1): ?>
            <div class="pagination">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?category_id=<?= $category_id ?>&keyword=<?= urlencode($keyword) ?>&page=<?= $i ?>"
                    class="<?= ($i == $page) ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
                <?php endfor; ?>
            </div>
            <?php endif; ?>

            <?php else: ?>
            <p style="text-align:center; font-size:1.5rem; color:#777; padding:50px;">
                Không tìm thấy sản phẩm nào phù hợp với "<strong><?= htmlspecialchars($keyword) ?></strong>"
            </p>
            <?php endif; ?>
        </div>
    </div>

    <div id="toast"></div>

    <script>
    function showToast(message) {
        const toast = document.getElementById('toast');
        toast.innerText = message;
        toast.style.display = 'block';
        setTimeout(() => toast.style.display = 'none', 2000);
    }

    document.querySelectorAll('.add-to-cart').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            fetch('add_to_cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'id=' + id
                })
                .then(() => {
                    showToast('Đã thêm vào giỏ hàng!');
                    // Cập nhật lại badge
                    location.reload();
                });
        });
    });
    </script>
</body>

</html>