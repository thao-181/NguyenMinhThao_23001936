<?php

require_once __DIR__ . "/model/product.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if ($id === false || $id === null || $id <= 0) {
    die("ID sản phẩm không hợp lệ.");
}

$product = getProductById($id);

if (!$product) {
    require_once __DIR__ . "/view/header.php";

    echo "<h2>Xóa sản phẩm</h2>";
    echo "<p>Sản phẩm không tồn tại.</p>";
    echo '<p><a href="product_list.php">Quay lại danh sách</a></p>';

    require_once __DIR__ . "/view/footer.php";
    exit;
}

$success = deleteProduct($id);

require_once __DIR__ . "/view/header.php";

?>

<h2>Xóa sản phẩm</h2>

<?php if ($success): ?>

    <p>
        Đã xóa sản phẩm:
        <strong><?= htmlspecialchars($product["name"]) ?></strong>
    </p>

<?php else: ?>

    <p>Không thể xóa sản phẩm.</p>

<?php endif; ?>

<p>
    <a href="product_list.php">Quay lại danh sách sản phẩm</a>
</p>

<?php

require_once __DIR__ . "/view/footer.php";

?>
