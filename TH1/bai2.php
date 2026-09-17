<?php
// Bài 2: Tách hàm xử lý sinh viên

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

// 1. Tính và trả về điểm trung bình của danh sách sinh viên
function calculateAverageScore($students) {
    $totalScore = 0;
    foreach ($students as $student) {
        $totalScore += $student["score"];
    }
    return $totalScore / count($students);
}

// 2. Trả về xếp loại sinh viên:
function getRank($score) {
    if ($score >= 8) {
        return "Giỏi";
    }
    if ($score >= 6.5) {
        return "Khá";
    }
    if ($score >= 5) {
        return "Trung bình";
    }
    return "Yếu";
}

// 3. Hiển thị thông tin một sinh viên:
function displayStudent($student) {
    echo "Họ tên: " . $student["name"]  . "\n";
    echo "Tuổi  : " . $student["age"]   . "\n";
    echo "Điểm  : " . $student["score"] . "\n";
    echo "Xếp loại: " . getRank($student["score"]) . "\n";
    echo "------------------------------\n";
}


echo "===== THÔNG TIN SINH VIÊN =====\n\n";


foreach ($students as $student) {
    displayStudent($student);
}


$averageScore = calculateAverageScore($students);
echo "Điểm trung bình: " . round($averageScore, 2) . "\n";
