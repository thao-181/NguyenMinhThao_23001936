<?php
declare(strict_types=1);

// Mặc định dành cho XAMPP. Có thể sửa ở đây hoặc đặt biến môi trường TH8_DB_*.
return [
    'host' => getenv('TH8_DB_HOST') ?: '127.0.0.1',
    'port' => getenv('TH8_DB_PORT') ?: '3306',
    'name' => getenv('TH8_DB_NAME') ?: 'th8_products',
    'user' => getenv('TH8_DB_USER') ?: 'root',
    'password' => getenv('TH8_DB_PASSWORD') ?: '',
];
