<?php
// Bài 3: Xử lý danh sách sinh viên

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

// 1. Tìm và trả về sinh viên có điểm cao nhất
function findBestStudent($students) {
    $best = $students[0];
    foreach ($students as $student) {
        if ($student["score"] > $best["score"]) {
            $best = $student;
        }
    }
    return $best;
}

// 2. Tìm và trả về sinh viên có điểm thấp nhất
function findWorstStudent($students) {
    $worst = $students[0];
    foreach ($students as $student) {
        if ($student["score"] < $worst["score"]) {
            $worst = $student;
        }
    }
    return $worst;
}

// 3. Đếm số sinh viên đạt (điểm >= 5)
function countPassedStudents($students) {
    $count = 0;
    foreach ($students as $student) {
        if ($student["score"] >= 5) {
            $count++;
        }
    }
    return $count;
}

// 4. Tìm sinh viên theo tên (không phân biệt hoa thường) và trả về sinh viên tìm được
function findStudentByName($students, $name) {
    foreach ($students as $student) {
        if (strcasecmp($student["name"], $name) === 0) {
            return $student;
        }
    }
    return null;
}

// ===== Phần code chính: gọi các function và in kết quả =====
echo "===== KẾT QUẢ XỬ LÝ DANH SÁCH SINH VIÊN =====\n\n";

// 1. Sinh viên có điểm cao nhất
$best = findBestStudent($students);
echo "Sinh viên điểm cao nhất : {$best["name"]} ({$best["score"]} điểm)\n";

// 2. Sinh viên có điểm thấp nhất
$worst = findWorstStudent($students);
echo "Sinh viên điểm thấp nhất: {$worst["name"]} ({$worst["score"]} điểm)\n";

// 3. Số sinh viên đạt
$passed = countPassedStudents($students);
echo "Số sinh viên đạt        : {$passed} / " . count($students) . "\n";

// 4. Tìm sinh viên theo tên
echo "\n--- Tìm sinh viên theo tên ---\n";

$found = findStudentByName($students, "Tran Thi Binh");
if ($found !== null) {
    echo "Tìm thấy: {$found["name"]} - Tuổi: {$found["age"]} - Điểm: {$found["score"]}\n";
} else { 
    echo "Không tìm thấy sinh viên \"Tran Thi Binh\"!\n";
}


$found = findStudentByName($students, "Nguyen Van Teo");
if ($found !== null) {
    echo "{$found["name"]} - Tuổi: {$found["age"]} - Điểm: {$found["score"]}\n";
} else {
    echo "Không tìm thấy sinh viên \"Nguyen Van Teo\"!\n";
}
