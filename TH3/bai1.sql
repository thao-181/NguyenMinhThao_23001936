-- 1. Tạo database
CREATE DATABASE shopping_cart;

USE shopping_cart;

-- 2. Tạo bảng
CREATE TABLE cart_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL
);

-- 3. Thêm 5 sản phẩm
INSERT INTO cart_items (name, price, quantity) VALUES
('Laptop Dell', 20000000, 2),
('Chuột Logitech', 500000, 6),
('Bàn phím cơ', 1200000, 4),
('Tai nghe Sony', 2500000, 8),
('USB 64GB', 180000, 10);

-- 4. Hiển thị toàn bộ sản phẩm
SELECT * FROM cart_items;

-- 5. Sản phẩm có giá > 100000
SELECT *
FROM cart_items
WHERE price > 100000;

-- 6. Sản phẩm có số lượng > 5
SELECT *
FROM cart_items
WHERE quantity > 5;

-- 7. Sắp xếp giá giảm dần
SELECT *
FROM cart_items
ORDER BY price DESC;

-- 8. Cập nhật giá
UPDATE cart_items
SET price = 550000
WHERE name = 'Chuột Logitech';

-- 9. Cập nhật số lượng
UPDATE cart_items
SET quantity = 7
WHERE name = 'Bàn phím cơ';

-- 10. Xóa sản phẩm
DELETE FROM cart_items
WHERE name = 'USB 64GB';

-- 11. Hiển thị thành tiền
SELECT
    name,
    price,
    quantity,
    price * quantity AS total
FROM cart_items;

-- 12. Tính tổng tiền
SELECT SUM(price * quantity) AS total_cart
FROM cart_items;