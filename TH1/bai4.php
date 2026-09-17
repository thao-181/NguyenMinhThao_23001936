<?php
// Bài 4: Chuyển sang lập trình hướng đối tượng (OOP)


class Student {
  
    public $name;
    public $age;
    public $score;

   
    public function __construct($name, $age, $score) {
        $this->name  = $name;
        $this->age   = $age;
        $this->score = $score;
    }

    // Trả về xếp loại:

    public function getRank() {
        if ($this->score >= 8) {
            return "Giỏi";
        }
        if ($this->score >= 6.5) {
            return "Khá";
        }
        if ($this->score >= 5) {
            return "Trung bình";
        }
        return "Yếu";
    }

    // Kiểm tra sinh viên đạt hay không (điểm >= 5)
    public function isPassed() {
        return $this->score >= 5;
    }

    // Hiển thị thông tin sinh viên
    public function display() {
        echo "Họ tên  : " . $this->name . "\n";
        echo "Tuổi    : " . $this->age  . "\n";
        echo "Điểm    : " . $this->score . "\n";
        echo "Xếp loại: " . $this->getRank() . "\n";
        echo "Kết quả : " . ($this->isPassed() ? "Đạt" : "Chưa đạt") . "\n";
        echo "------------------------------\n";
    }
}

// Hàm tìm sinh viên có điểm cao nhất
function findBestStudent($students) {
    $best = $students[0];
    foreach ($students as $student) {
        if ($student->score > $best->score) {
            $best = $student;
        }
    }
    return $best;
}

// Hàm đếm số sinh viên đạt
function countPassedStudents($students) {
    $count = 0;
    foreach ($students as $student) {
        if ($student->isPassed()) {
            $count++;
        }
    }
    return $count;
}

// Hàm tính điểm trung bình của lớp
function calculateAverageScore($students) {
    $totalScore = 0;
    foreach ($students as $student) {
        $totalScore += $student->score;
    }
    return $totalScore / count($students);
}

// ===== Phần code chính =====

// Tạo các object Student
$student1 = new Student("Nguyen Van An", 20, 8.5);
$student2 = new Student("Tran Thi Binh", 21, 6.5);
$student3 = new Student("Le Van Cuong", 19, 4.5);
$student4 = new Student("Pham Thi Dung", 20, 7.5);

// Tạo danh sách các object Student
$students = [$student1, $student2, $student3, $student4];

echo "===== THÔNG TIN SINH VIÊN =====\n\n";

// Duyệt danh sách và gọi display() để hiển thị thông tin
foreach ($students as $student) {
    $student->display();
}

// Tìm sinh viên có điểm cao nhất
$best = findBestStudent($students);
echo "Sinh viên điểm cao nhất : {$best->name} ({$best->score} điểm)\n";

// Đếm số sinh viên đạt
$passed = countPassedStudents($students);
echo "Số sinh viên đạt        : {$passed} / " . count($students) . "\n";

// Tính điểm trung bình của lớp
$averageScore = calculateAverageScore($students);
echo "Điểm trung bình của lớp : " . round($averageScore, 2) . "\n";
