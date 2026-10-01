
-- 1. Tạo database
CREATE DATABASE movie_management;

USE movie_management;

-- 2. Tạo bảng movies
CREATE TABLE movies (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    total_seats INT NOT NULL,
    available_seats INT NOT NULL
);

-- 3. Thêm 5 bộ phim
INSERT INTO movies (title, price, total_seats, available_seats) VALUES
('Avengers: Endgame', 120000, 100, 30),
('Avatar: The Way of Water', 150000, 120, 45),
('Batman', 90000, 80, 60),
('Spider-Man: No Way Home', 110000, 100, 20),
('The Conjuring', 100000, 90, 70);

-- 4. Hiển thị toàn bộ danh sách phim
SELECT *
FROM movies;

-- 5. Phim có giá vé > 100000
SELECT *
FROM movies
WHERE price > 100000;

-- 6. Phim còn > 50 ghế
SELECT *
FROM movies
WHERE available_seats > 50;

-- 7. Sắp xếp giá vé giảm dần
SELECT *
FROM movies
ORDER BY price DESC;

-- 8. Cập nhật số ghế còn lại
UPDATE movies
SET available_seats = 15
WHERE title = 'Spider-Man: No Way Home';

-- 9. Xóa một bộ phim
DELETE FROM movies
WHERE title = 'Batman';

-- 10. Hiển thị số vé đã bán
SELECT
    title,
    total_seats,
    available_seats,
    total_seats - available_seats AS sold_seats
FROM movies;

-- 11. Tính doanh thu từng phim
SELECT
    title,
    price,
    total_seats - available_seats AS sold_seats,
    (total_seats - available_seats) * price AS revenue
FROM movies;

-- 12. Tính tổng doanh thu
SELECT
    SUM((total_seats - available_seats) * price) AS total_revenue
FROM movies;

-- 13. Tìm phim có số vé bán ra nhiều nhất
SELECT *
FROM movies
WHERE (total_seats - available_seats) = (
    SELECT MAX(total_seats - available_seats)
    FROM movies
);