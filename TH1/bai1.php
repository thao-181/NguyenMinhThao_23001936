<?php
// Bài 1: Làm quen với biến, mảng và vòng lặp

// Danh sách sinh viên
$students = [
    [
        "name"  => "Nguyen Van An",
        "age"   => 20,
        "score" => 8.5
    ],
    [
        "name"  => "Tran Thi Binh",
        "age"   => 21,
        "score" => 6.5
    ],
    [
        "name"  => "Le Van Cuong",
        "age"   => 19,
        "score" => 4.5
    ],
    [
        "name"  => "Pham Thi Dung",
        "age"   => 20,
        "score" => 7.5
    ]
];

echo "===== THÔNG TIN SINH VIÊN =====\n\n";

// Duyệt danh sách sinh viên bằng foreach và in thông tin từng sinh viên
foreach ($students as $student) {
    echo "Họ tên: " . $student["name"] . "\n";
    echo "Tuổi  : " . $student["age"] . "\n";
    echo "Điểm  : " . $student["score"] . "\n";
    echo "------------------------------\n";
}

// Tính tổng điểm của tất cả sinh viên
$totalScore = 0;
foreach ($students as $student) {
    $totalScore += $student["score"];
}

// Tính điểm trung bình của tất cả sinh viên
$averageScore = $totalScore / count($students);

echo "Điểm trung bình: " . number_format($averageScore, 2) . "\n";

