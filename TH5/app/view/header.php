<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentUser = $_SESSION["username"] ?? null;

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý giỏ hàng</title>
</head>
<body>

<header>
    <h1>QUẢN LÝ GIỎ HÀNG</h1>

    <?php if ($currentUser !== null): ?>
        <p>
            Xin chào, <strong><?= htmlspecialchars($currentUser) ?></strong> |
            <a href="logout.php">Đăng xuất</a>
        </p>

        <nav>
            <a href="product_list.php">Danh sách sản phẩm</a> |
            <a href="product_add.php">Thêm sản phẩm</a>
        </nav>
    <?php endif; ?>

    <hr>
</header>