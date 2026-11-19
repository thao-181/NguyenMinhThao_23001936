<?php
declare(strict_types=1);

// Mặc định dành cho XAMPP. Có thể sửa ở đây hoặc đặt biến môi trường TH9_DB_*.
return [
    'host' => getenv('TH9_DB_HOST') ?: '127.0.0.1',
    'port' => getenv('TH9_DB_PORT') ?: '3306',
    'name' => getenv('TH9_DB_NAME') ?: 'th9_products',
    'user' => getenv('TH9_DB_USER') ?: 'root',
    'password' => getenv('TH9_DB_PASSWORD') ?: '',
];
