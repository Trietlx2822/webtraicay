<?php
/*session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
include 'db.php';

// Kiểm tra quyền admin dựa trên is_admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== 1) {
    echo "<h2 style='text-align:center;color:red;'>Bạn không có quyền truy cập chức năng quản trị.</h2>";
    exit;
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$product_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$message = '';

if ($action === 'delete' && $product_id) {
    $stmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->close();
    $message = "Đã xóa sản phẩm.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = $_POST['name'] ?? '';
    $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT) ?: 0;
    $image = $_POST['image'] ?? '';
    $category_id = filter_input(INPUT_POST, 'category_id', FILTER_VALIDATE_INT) ?: 0;

    if ($action === 'add') {
        $stmt = $conn->prepare("INSERT INTO products (name, price, image, category_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sdsi", $name, $price, $image, $category_id);
        $stmt->execute();
        $stmt->close();
        $message = "Đã thêm sản phẩm.";
    } elseif ($action === 'edit' && $product_id) {
        $stmt = $conn->prepare("UPDATE products SET name=?, price=?, image=?, category_id=? WHERE product_id=?");
        $stmt->bind_param("sdsii", $name, $price, $image, $category_id, $product_id);
        $stmt->execute();
        $stmt->close();
        $message = "Đã cập nhật sản phẩm.";
    }
}

$product = ['name' => '', 'price' => '', 'image' => '', 'category_id' => ''];
if ($action === 'edit' && $product_id) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc() ?: $product;
    $stmt->close();
}

$categories = $conn->query("SELECT * FROM categories");
$all_products = $conn->query("SELECT p.*, c.name AS category_name FROM products p JOIN categories c ON p.category_id = c.category_id ORDER BY p.product_id DESC");*/
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
include 'db.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== 1) {
    echo "<h2 style='text-align:center;color:red;'>Bạn không có quyền truy cập chức năng quản trị.</h2>";
    exit;
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$product_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$message = '';

$upload_dir = 'images/products/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// XÓA SẢN PHẨM
if ($action === 'delete' && $product_id) {
    $stmt = $conn->prepare("SELECT image FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $old_image = $stmt->get_result()->fetch_assoc()['image'] ?? '';
    if ($old_image && file_exists($old_image)) {
        unlink($old_image);
    }

    $stmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $message = "Đã xóa sản phẩm.";
}

// THÊM / SỬA SẢN PHẨM
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    $name = trim($_POST['name'] ?? '');
    $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT) ?: 0;
    $category_id = filter_input(INPUT_POST, 'category_id', FILTER_VALIDATE_INT) ?: 0;
    $image_path = '';

    // XỬ LÝ UPLOAD ẢNH
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['image_file'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (in_array($ext, $allowed) && $file['size'] <= 5 * 1024 * 1024) { // max 5MB
            $new_name = uniqid('prod_') . '.' . $ext;
            $image_path = $upload_dir . $new_name;

            if (move_uploaded_file($file['tmp_name'], $image_path)) {
                // Xóa ảnh cũ nếu đang sửa
                if ($action === 'edit' && $product_id) {
                    $old = $conn->query("SELECT image FROM products WHERE product_id = $product_id")->fetch_assoc()['image'] ?? '';
                    if ($old && file_exists($old)) unlink($old);
                }
            } else {
                $message = "Lỗi upload ảnh!";
                $image_path = '';
            }
        } else {
            $message = "Ảnh không hợp lệ (chỉ cho phép jpg, png, gif, webp - tối đa 5MB)";
        }
    }

    if ($message === '' && $name && $price > 0 && $category_id > 0) {
        if ($action === 'add') {
            $stmt = $conn->prepare("INSERT INTO products (name, price, image, category_id) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sdsi", $name, $price, $image_path, $category_id);
            $stmt->execute();
            $message = "Đã thêm sản phẩm thành công!";
        } elseif ($action === 'edit' && $product_id) {
            // Nếu không upload ảnh mới → giữ ảnh cũ
            $final_image = $image_path ?: $product['image'];

            $stmt = $conn->prepare("UPDATE products SET name=?, price=?, image=?, category_id=? WHERE product_id=?");
            $stmt->bind_param("sdsii", $name, $price, $final_image, $category_id, $product_id);
            $stmt->execute();
            $message = "Đã cập nhật sản phẩm thành công!";
        }
    }
}

// LẤY DỮ LIỆU CHO FORM SỬA
$product = ['name' => '', 'price' => '', 'image' => '', 'category_id' => ''];
if ($action === 'edit' && $product_id) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc() ?: $product;
}

$categories = $conn->query("SELECT * FROM categories");
$all_products = $conn->query("SELECT p.*, c.name AS category_name FROM products p JOIN categories c ON p.category_id = c.category_id ORDER BY p.product_id DESC");
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Quản lý sản phẩm</title>
    <style>
    .header {
        width: 100%;
        background: #fff;
        padding: 20px 20px;
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

    body {
        font-family: Arial, sans-serif;
        padding: 20px;
        background-color: #f4f4f4;
    }

    h2,
    h3 {
        text-align: center;
        color: #333;
    }

    form {
        max-width: 500px;
        margin: 0 auto;
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    label {
        display: block;
        margin: 10px 0 5px;
        font-weight: bold;
    }

    input,
    select {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    button {
        width: 100%;
        padding: 12px;
        background: #3bb77e;
        border: none;
        color: white;
        font-size: 1rem;
        border-radius: 4px;
        cursor: pointer;
        transition: background 0.3s;
    }

    button:hover {
        background: #2a9d8f;
    }

    .message {
        color: green;
        text-align: center;
        margin: 10px 0;
    }

    .products-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
        margin-top: 40px;
    }

    .product-card {
        background: white;
        width: 250px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        text-align: center;
        padding-bottom: 15px;
    }

    .product-card img {
        width: 100%;
        height: 150px;
        object-fit: cover;
    }

    .product-card h4 {
        margin: 10px 0;
        font-size: 1.1rem;
        color: #333;
    }

    .product-card p {
        margin: 5px 0;
        color: #666;
    }

    .product-card .actions {
        margin-top: 10px;
    }

    .product-card .actions a {
        text-decoration: none;
        padding: 8px 15px;
        margin: 0 5px;
        border-radius: 4px;
        font-size: 0.9rem;
        transition: background 0.3s;
    }

    .product-card .actions .edit {
        background: #3bb77e;
        color: white;
    }

    .product-card .actions .edit:hover {
        background: #2a9d8f;
    }

    .product-card .actions .delete {
        background: #e63946;
        color: white;
    }

    .product-card .actions .delete:hover {
        background: #d00000;
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
            <div id="login-btn" class="fas fa-user">
            </div>
            <div id="sign-out" class="fas fa-sign-out-alt"></div>
            <script>
            document.getElementById("sign-out").addEventListener("click", function() {
                // Chuyển hướng đến trang logout.php khi nhấn vào div
                window.location.href = "logout.php";
            });
            </script>
        </div>
    </header>
    <h2>Quản lý sản phẩm</h2>

    <?php if ($message): ?>
    <p class="message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="action" value="<?= $action === 'edit' ? 'edit' : 'add' ?>">
        <?php if ($action === 'edit'): ?>
        <input type="hidden" name="id" value="<?= $product_id ?>">
        <?php endif; ?>

        <label>Tên sản phẩm</label>
        <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>

        <label>Giá (USD)</label>
        <input type="number" step="0.01" name="price" value="<?= $product['price'] ?>" required>

        <label>Hình ảnh</label>
        <?php if ($action === 'edit' && $product['image']): ?>
        <p><img src="<?= $product['image'] ?>" width="150" style="margin:10px 0;"></p>
        <p><small>Để trống nếu không muốn thay ảnh</small></p>
        <?php endif; ?>
        <input type="file" name="image_file" accept="image/*" <?= $action === 'add' ? 'required' : '' ?>>

        <label>Danh mục</label>
        <select name="category_id" required>
            <option value="">Chọn danh mục</option>
            <?php while ($cat = $categories->fetch_assoc()): ?>
            <option value="<?= $cat['category_id'] ?>"
                <?= $cat['category_id'] == $product['category_id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat['name']) ?>
            </option>
            <?php endwhile;
            $categories->data_seek(0); ?>
        </select>

        <button type="submit">Lưu</button>
    </form>


    <h3 style="margin-top: 60px;">Danh sách sản phẩm</h3>
    <div class="products-container">
        <?php while ($row = $all_products->fetch_assoc()): ?>
        <div class="product-card">
            <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
            <h4><?= htmlspecialchars($row['name']) ?></h4>
            <p>Giá: $<?= number_format($row['price'], 2) ?></p>
            <p>Danh mục: <?= htmlspecialchars($row['category_name']) ?></p>
            <div class="actions">
                <a href="update.php?action=edit&id=<?= $row['product_id'] ?>" class="edit">Sửa</a>
                <a href="update.php?action=delete&id=<?= $row['product_id'] ?>" class="delete"
                    onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</a>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</body>

</html>
<?php
$conn->close();
?>