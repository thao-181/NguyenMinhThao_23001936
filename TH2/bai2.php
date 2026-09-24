<?php

class Movie
{
    private $id;
    private $title;
    private $price;
    private $totalSeats;
    private $availableSeats;

    public function __construct($id, $title, $price, $totalSeats)
    {
        $this->id = $id;
        $this->title = $title;
        $this->price = $price;
        $this->totalSeats = $totalSeats;
        $this->availableSeats = $totalSeats;
    }

    public function bookTicket($quantity)
    {
        if ($quantity <= 0) {
            echo "Không thể đặt vé. Số lượng vé phải lớn hơn 0.<br>";
            return false;
        }

        if ($quantity > $this->availableSeats) {
            echo "Không thể đặt vé cho phim "
                . $this->title
                . ". Số vé yêu cầu vượt quá số ghế còn lại.<br>";

            return false;
        }

        $this->availableSeats -= $quantity;

        echo "Đặt thành công "
            . $quantity
            . " vé cho phim "
            . $this->title
            . ".<br>";

        return true;
    }

    public function cancelTicket($quantity)
    {
        if ($quantity <= 0) {
            echo "Không thể hủy vé. Số lượng vé phải lớn hơn 0.<br>";
            return false;
        }

        $soldSeats = $this->getSoldSeats();

        if ($quantity > $soldSeats) {
            echo "Không thể hủy "
                . $quantity
                . " vé cho phim "
                . $this->title
                . ". Số vé đã bán không đủ.<br>";

            return false;
        }

        $this->availableSeats += $quantity;

        echo "Hủy thành công "
            . $quantity
            . " vé của phim "
            . $this->title
            . ".<br>";

        return true;
    }

    public function getSoldSeats()
    {
        return $this->totalSeats - $this->availableSeats;
    }

    public function getRevenue()
    {
        return $this->getSoldSeats() * $this->price;
    }

    public function displayInfo()
    {
        echo "<h3>Thông tin phim</h3>";

        echo "Mã phim: " . $this->id . "<br>";
        echo "Tên phim: " . $this->title . "<br>";
        echo "Giá vé: "
            . number_format($this->price, 0, ',', '.')
            . " VNĐ<br>";

        echo "Tổng số ghế: "
            . $this->totalSeats
            . "<br>";

        echo "Số ghế còn lại: "
            . $this->availableSeats
            . "<br>";

        echo "Số vé đã bán: "
            . $this->getSoldSeats()
            . "<br>";

        echo "Doanh thu: "
            . number_format($this->getRevenue(), 0, ',', '.')
            . " VNĐ<br>";
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }
}


/*
| FUNCTION XỬ LÝ DANH SÁCH PHIM
*/

function findMovieById($movies, $id)
{

    if (empty($movies)) {
        return null;
    }

    foreach ($movies as $movie) {
        if ($movie->getId() == $id) {
            return $movie;
        }
    }

    
    return null;
}


function getTotalRevenue($movies)
{
    
    if (empty($movies)) {
        return 0;
    }

    $totalRevenue = 0;

    foreach ($movies as $movie) {
        $totalRevenue += $movie->getRevenue();
    }

    return $totalRevenue;
}


function getBestSellingMovie($movies)
{
    
    if (empty($movies)) {
        return null;
    }

    $bestSellingMovie = null;
    $maxSoldSeats = 0;

    foreach ($movies as $movie) {
        $soldSeats = $movie->getSoldSeats();

        if ($bestSellingMovie === null || $soldSeats > $maxSoldSeats) {
            $maxSoldSeats = $soldSeats;
            $bestSellingMovie = $movie;
        }
    }

    return $bestSellingMovie;
}


/*
| CHƯƠNG TRÌNH CHÍNH
*/

echo "<h1>QUẢN LÝ VÉ XEM PHIM</h1>";


/*
| 1. Tạo danh sách các object Movie
*/

$movie1 = new Movie(
    1,
    "Avengers",
    100000,
    100
);

$movie2 = new Movie(
    2,
    "Avatar",
    120000,
    80
);

$movie3 = new Movie(
    3,
    "Batman",
    90000,
    120
);


$movies = [
    $movie1,
    $movie2,
    $movie3
];


/*
| 2. Đặt vé cho phim Avengers
*/

echo "<h2>Đặt vé</h2>";

$avengers = findMovieById($movies, 1);

if ($avengers !== null) {
    $avengers->bookTicket(30);
}


/*
| 3. Đặt vé cho phim Avatar
*/

$avatar = findMovieById($movies, 2);

if ($avatar !== null) {
    $avatar->bookTicket(40);
}


/*
| 4. Hủy một số vé của Avengers
*/

echo "<h2>Hủy vé</h2>";

if ($avengers !== null) {
    $avengers->cancelTicket(10);
}


/*
| 5. Hiển thị thông tin tất cả phim
*/

echo "<h2>DANH SÁCH PHIM</h2>";

foreach ($movies as $movie) {
    $movie->displayInfo();
    echo "<hr>";
}


/*
| 6. Tính tổng doanh thu
*/

$totalRevenue = getTotalRevenue($movies);

echo "<h2>TỔNG DOANH THU</h2>";

echo number_format(
    $totalRevenue,
    0,
    ',',
    '.'
) . " VNĐ<br>";


/*
| 7. Tìm phim bán được nhiều vé nhất
*/

$bestSellingMovie = getBestSellingMovie($movies);

echo "<h2>PHIM BÁN ĐƯỢC NHIỀU VÉ NHẤT</h2>";

if ($bestSellingMovie !== null) {

    echo "Tên phim: "
        . $bestSellingMovie->getTitle()
        . "<br>";

    echo "Số vé đã bán: "
        . $bestSellingMovie->getSoldSeats()
        . "<br>";

} else {

    echo "Danh sách phim đang rỗng.<br>";
}


/*
| KIỂM TRA CÁC TRƯỜNG HỢP KHÔNG HỢP LỆ
*/

echo "<hr>";

echo "<h2>KIỂM TRA TRƯỜNG HỢP KHÔNG HỢP LỆ</h2>";

$movie1->bookTicket(0);

$movie1->bookTicket(1000);

$movie1->cancelTicket(0);

$movie1->cancelTicket(1000);

$movieNotFound = findMovieById($movies, 999);

if ($movieNotFound === null) {
    echo "Không tìm thấy phim có ID = 999.<br>";
}

$emptyMovies = [];

echo "Doanh thu danh sách rỗng: "
    . getTotalRevenue($emptyMovies)
    . " VNĐ<br>";

if (getBestSellingMovie($emptyMovies) === null) {
    echo "Không thể tìm phim bán chạy vì danh sách phim đang rỗng.<br>";
}
?>