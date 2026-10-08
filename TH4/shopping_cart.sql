CREATE DATABASE IF NOT EXISTS shopping_cart
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE shopping_cart;

CREATE TABLE IF NOT EXISTS products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL
);

INSERT INTO products (name, price, quantity) VALUES
('iPhone 17', 22990000.00, 7),
('Samsung Galaxy S26', 18990000.00, 9),
('iPad Air', 15990000.00, 6),
('MacBook Air M4', 24990000.00, 5),
('Màn hình LG 24 inch', 3500000.00, 12),
('Loa Bluetooth JBL', 2100000.00, 8),
('USB Kingston 64GB', 180000.00, 20),
('Ổ cứng SSD Samsung 1TB', 2500000.00, 10),
('Sạc nhanh Anker', 750000.00, 15),
('Balo laptop Lenovo', 950000.00, 11);
