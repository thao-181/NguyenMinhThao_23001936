<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
ini_set('display_errors', '0');

function respond(int $status, array $body): never
{
    http_response_code($status);
    echo json_encode($body, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
    exit;
}

function connectDatabase(): PDO
{
    $config = require __DIR__ . '/../config/database.php';

    return new PDO(
        "mysql:host={$config['host']};port={$config['port']};dbname={$config['name']};charset=utf8mb4",
        $config['user'],
        $config['password'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
}

function positiveInteger(mixed $value, int $maximum = PHP_INT_MAX): int|false
{
    if (!is_string($value) || !ctype_digit($value)) {
        return false;
    }

    return filter_var(ltrim($value, '0'), FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1, 'max_range' => $maximum],
    ]);
}

function removeProductImage(?string $image): void
{
    // Chỉ dọn file có tên do API upload tạo ra.
    if ($image === null || !preg_match('~\Auploads/[a-f0-9]{32}\.(?:jpg|png)\z~', $image)) {
        return;
    }

    $file = __DIR__ . '/../' . $image;
    if (is_file($file) && !unlink($file)) {
        error_log('[TH9] Không thể xóa ảnh: ' . $image);
    }
}
