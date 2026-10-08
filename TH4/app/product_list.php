<?php

require_once __DIR__ . "/model/product.php";

$products = getAllProducts();

require_once __DIR__ . "/view/header.php";

?>

<h2>Danh sách sản phẩm</h2>



<?php if (count($products) > 0): ?>
<div style="width: 70%; margin: 0 auto;">
    <table border="1" cellpadding="8" cellspacing="0" style="margin: 0 auto; width: 100%;">
        
    <thead style="background-color: #e8d1d1;">
        <tr>
            <th>ID</th>
            <th>Tên sản phẩm</th>
            <th>Giá</th>
            <th>Số lượng</th>
            <th>Chức năng</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($products as $product): ?>
            <tr >
                <td ><?= htmlspecialchars($product["id"]) ?></td>

                <td>
                    <?= htmlspecialchars($product["name"]) ?>
                </td>

                <td>
                    <?= number_format((float)$product["price"], 2, ".", ",") ?>
                </td>

                <td><?= htmlspecialchars($product["quantity"]) ?></td>

                <td>
                    <a href="product_edit.php?id=<?= $product["id"] ?>">
                        Sửa
                    </a>

                    |

                    <a
                        href="product_delete.php?id=<?= $product["id"] ?>"
                        onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này không?');"
                    >
                        Xóa
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>


<button style="margin: 0 0 0 auto; display: block; margin-top: 10px;">
    <a href="product_add.php">Thêm sản phẩm</a>
</button>

</div>



<?php else: ?>

<p>Chưa có sản phẩm nào.</p>

<?php endif; ?>

<?php

require_once __DIR__ . "/view/footer.php";

?>
