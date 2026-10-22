<?php

$prices = [350000, 280000, 650000, 520000, 1250000];

function calculateTotal($prices)
{
    $total = 0;

    foreach ($prices as $price) {
        $total = $total + $price;
    }

    return $total;
}

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

$totalPrice = calculateTotal($prices);
$maxPrice = findMaxPrice($prices);

echo "<h2>Danh sách giá sản phẩm</h2>";

foreach ($prices as $index => $price) {
    echo "Sản phẩm " . ($index + 1) . ": ";
    echo number_format($price, 0, ',', '.') . " đồng<br>";
}

echo "<br>";
echo "Tổng giá trị sản phẩm: " . number_format($totalPrice, 0, ',', '.') . " đồng<br>";
echo "Giá sản phẩm cao nhất: " . number_format($maxPrice, 0, ',', '.') . " đồng";
?>
