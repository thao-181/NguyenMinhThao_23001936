<?php
// Khai báo tên và điểm của học sinh
$name = "Nguyễn Văn An";
$score = 8.5;

// Xếp loại học sinh
if ($score >= 8) {
    $classification = "Giỏi";
} elseif ($score >= 6.5) {
    $classification = "Khá";
} elseif ($score >= 5) {
    $classification = "Trung bình";
} else {
    $classification = "Yếu";
}

// Hiển thị kết quả
echo "<h2>Kết quả xếp loại học sinh</h2>";
echo "Họ và tên: " . $name . "<br>";
echo "Điểm: " . $score . "<br>";
echo "Xếp loại: " . $classification;
?>
