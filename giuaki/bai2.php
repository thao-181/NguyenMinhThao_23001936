<?php
// Khai báo mảng chứa giá của 5 sản phẩm
$prices = [350000, 280000, 650000, 520000, 1250000];

// Hàm tính tổng giá trị của tất cả sản phẩm
function calculateTotal($prices)
{
    $total = 0;

    foreach ($prices as $price) {
        $total = $total + $price;
    }

    return $total;
}

// Hàm tìm giá sản phẩm cao nhất
function findMaxPrice($prices)
{
    $maxPrice = $prices[0];

    foreach ($prices as $price) {
        if ($price > $maxPrice) {
            $maxPrice = $price;
        }
    }

    return $maxPrice;
}

// Gọi hai hàm để lấy kết quả
$totalPrice = calculateTotal($prices);
$maxPrice = findMaxPrice($prices);

// Hiển thị danh sách giá sản phẩm
echo "<h2>Danh sách giá sản phẩm</h2>";

foreach ($prices as $index => $price) {
    echo "Sản phẩm " . ($index + 1) . ": ";
    echo number_format($price, 0, ',', '.') . " đồng<br>";
}

// Hiển thị tổng giá trị và giá cao nhất
echo "<br>";
echo "Tổng giá trị sản phẩm: " . number_format($totalPrice, 0, ',', '.') . " đồng<br>";
echo "Giá sản phẩm cao nhất: " . number_format($maxPrice, 0, ',', '.') . " đồng";
?>
