```php
<?php

class CartItem
{
    private string $name;
    private float $price;
    private int $quantity;

    public function __construct($name, $price, $quantity)
    {
        if ($price <= 0) {
            throw new InvalidArgumentException(
                "Giá sản phẩm phải lớn hơn 0."
            );
        }

        if ($quantity <= 0) {
            throw new InvalidArgumentException(
                "Số lượng sản phẩm phải lớn hơn 0."
            );
        }

        $this->name = $name;
        $this->price = $price;
        $this->quantity = $quantity;
    }

    public function getTotal()
    {
        return $this->price * $this->quantity;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }
}


class ShoppingCart
{
    private array $items = [];

    public function addItem($item)
    {
        if (!$item instanceof CartItem) {
            throw new InvalidArgumentException(
                "Chỉ có thể thêm object CartItem vào giỏ hàng."
            );
        }

        $this->items[] = $item;
    }

    public function removeItem($name)
    {
        foreach ($this->items as $index => $item) {
            if ($item->getName() === $name) {
                unset($this->items[$index]);

                $this->items = array_values($this->items);

                echo "Đã xóa sản phẩm: " . $name . "<br>";

                return;
            }
        }

        echo "Không tìm thấy sản phẩm: " . $name . "<br>";
    }

    public function calculateTotal()
    {
        $total = 0;

        foreach ($this->items as $item) {
            $total += $item->getTotal();
        }

        return $total;
    }

    public function displayCart()
    {
        if (empty($this->items)) {
            echo "<p>Giỏ hàng đang trống.</p>";
            return;
        }

        echo "<h2>Danh sách sản phẩm</h2>";

        echo "<table border='1' cellpadding='10' cellspacing='0'>";

        echo "<tr>";
        echo "<th>Tên sản phẩm</th>";
        echo "<th>Đơn giá</th>";
        echo "<th>Số lượng</th>";
        echo "<th>Thành tiền</th>";
        echo "</tr>";

        foreach ($this->items as $item) {
            echo "<tr>";

            echo "<td>" . $item->getName() . "</td>";

            echo "<td>"
                . number_format($item->getPrice(), 0, ',', '.')
                . " VNĐ</td>";

            echo "<td>" . $item->getQuantity() . "</td>";

            echo "<td>"
                . number_format($item->getTotal(), 0, ',', '.')
                . " VNĐ</td>";

            echo "</tr>";
        }

        echo "</table>";

        echo "<h3>Tổng tiền: "
            . number_format($this->calculateTotal(), 0, ',', '.')
            . " VNĐ</h3>";
    }
}


/*
| CHƯƠNG TRÌNH CHÍNH
*/

try {
    $cart = new ShoppingCart();

    $item1 = new CartItem("Laptop", 20000000, 1);
    $item2 = new CartItem("Chuột", 500000, 2);
    $item3 = new CartItem("Bàn phím", 1000000, 1);
    $item4 = new CartItem("Tai nghe", 1500000, 2);

    $cart->addItem($item1);
    $cart->addItem($item2);
    $cart->addItem($item3);
    $cart->addItem($item4);

    echo "<h1>GIỎ HÀNG MUA SẮM</h1>";

    $cart->displayCart();

    echo "<h3>";
    echo "Tổng tiền của giỏ hàng: "
        . number_format($cart->calculateTotal(), 0, ',', '.')
        . " VNĐ";
    echo "</h3>";

    echo "<hr>";
    echo "<h2>Xóa sản phẩm</h2>";

    $cart->removeItem("Bàn phím");

    echo "<h2>Giỏ hàng sau khi xóa</h2>";

    $cart->displayCart();

} catch (InvalidArgumentException $e) {

    echo "<p style='color:red;'>"
        . $e->getMessage()
        . "</p>";
}
?>
```
