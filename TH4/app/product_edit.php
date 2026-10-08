<?php

require_once __DIR__ . "/model/product.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if ($id === false || $id === null || $id <= 0) {
    die("ID sản phẩm không hợp lệ.");
}

$product = getProductById($id);

if (!$product) {
    die("Sản phẩm không tồn tại.");
}

$name = $product["name"];
$price = $product["price"];
$quantity = $product["quantity"];
$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"] ?? "");
    $price = trim($_POST["price"] ?? "");
    $quantity = trim($_POST["quantity"] ?? "");

    if ($name === "") {
        $errors[] = "Tên sản phẩm không được rỗng.";
    }

    if ($price === "" || !is_numeric($price) || (float)$price <= 0) {
        $errors[] = "Giá sản phẩm phải lớn hơn 0.";
    }

    if (
        $quantity === "" ||
        filter_var($quantity, FILTER_VALIDATE_INT) === false ||
        (int)$quantity < 0
    ) {
        $errors[] = "Số lượng phải là số nguyên lớn hơn hoặc bằng 0.";
    }

    if (empty($errors)) {

        $success = updateProduct(
            $id,
            $name,
            (float)$price,
            (int)$quantity
        );

        if ($success) {
            header("Location: product_list.php");
            exit;
        }

        $errors[] = "Không thể cập nhật sản phẩm.";
    }
}

require_once __DIR__ . "/view/header.php";

?>

<h2>Sửa sản phẩm</h2>

<?php if (!empty($errors)): ?>

    <ul>
        <?php foreach ($errors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
    </ul>

<?php endif; ?>

<form method="post" action="product_edit.php?id=<?= $id ?>">

    <p>
        <label for="name">Tên sản phẩm:</label><br>
        <input
            type="text"
            id="name"
            name="name"
            maxlength="100"
            value="<?= htmlspecialchars($name) ?>"
        >
    </p>

    <p>
        <label for="price">Giá:</label><br>
        <input
            type="number"
            id="price"
            name="price"
            min="0.01"
            step="0.01"
            value="<?= htmlspecialchars($price) ?>"
        >
    </p>

    <p>
        <label for="quantity">Số lượng:</label><br>
        <input
            type="number"
            id="quantity"
            name="quantity"
            min="0"
            step="1"
            value="<?= htmlspecialchars($quantity) ?>"
        >
    </p>

    <button type="submit">Cập nhật</button>

    <a href="product_list.php">Quay lại</a>

</form>

<?php

require_once __DIR__ . "/view/footer.php";

?>
