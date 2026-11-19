-- Cơ sở dữ liệu TH9 và 12 sản phẩm mẫu để kiểm tra phân trang.
CREATE DATABASE IF NOT EXISTS th9_products
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE th9_products;

CREATE TABLE products (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    price INT NOT NULL,
    stock INT NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    CONSTRAINT chk_products_name CHECK (CHAR_LENGTH(TRIM(name)) BETWEEN 1 AND 100),
    CONSTRAINT chk_products_price CHECK (price > 0),
    CONSTRAINT chk_products_stock CHECK (stock >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO products (name, price, stock) VALUES
    ('Bút bi', 5000, 20),
    ('Vở ô ly', 12000, 15),
    ('Thước kẻ', 8000, 0),
    ('Bút chì', 6000, 30),
    ('Tẩy bút chì', 4000, 25),
    ('Gọt bút chì', 7000, 17),
    ('Sổ tay', 24000, 12),
    ('Bút dạ quang', 15000, 9),
    ('Giấy ghi chú', 11000, 18),
    ('Kẹp giấy', 9000, 40),
    ('Bìa hồ sơ', 10000, 14),
    ('Keo dán', 13000, 8);
