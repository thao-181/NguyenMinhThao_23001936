<?php
declare(strict_types=1);

// Câu 2: API CRUD. Không xuất HTML hoặc thông tin kết nối trong phản hồi.
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
ini_set('display_errors', '0');

function respond(int $status, array $body): never
{
    http_response_code($status);
    echo json_encode($body, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
    exit;
}

function readProduct(): array
{
    $contentType = strtolower(trim(explode(';', $_SERVER['CONTENT_TYPE'] ?? '')[0]));
    if ($contentType !== 'application/json') {
        respond(415, ['success' => false, 'message' => 'Vui lòng gửi dữ liệu dạng application/json.']);
    }

    try {
        $input = json_decode(file_get_contents('php://input'), false, 512, JSON_THROW_ON_ERROR);
    } catch (JsonException $e) {
        respond(400, ['success' => false, 'message' => 'Nội dung JSON không hợp lệ.']);
    }

    if (!is_object($input)) {
        respond(422, ['success' => false, 'message' => 'Dữ liệu phải là đối tượng có name, price và stock.']);
    }

    $name = isset($input->name) && is_string($input->name) ? trim($input->name) : '';
    if (mb_strlen($name, 'UTF-8') < 1 || mb_strlen($name, 'UTF-8') > 100) {
        respond(422, ['success' => false, 'message' => 'Tên sản phẩm sau khi bỏ khoảng trắng hai đầu phải có từ 1 đến 100 ký tự.']);
    }
    // Kiểm tra kiểu số nguyên và giới hạn cột INT trước khi ghi vào MySQL.
    if (!isset($input->price) || !is_int($input->price) || $input->price <= 0 || $input->price > 2147483647) {
        respond(422, ['success' => false, 'message' => 'Đơn giá phải là số nguyên từ 1 đến 2.147.483.647 đồng.']);
    }
    if (!isset($input->stock) || !is_int($input->stock) || $input->stock < 0 || $input->stock > 2147483647) {
        respond(422, ['success' => false, 'message' => 'Số lượng tồn phải là số nguyên từ 0 đến 2.147.483.647.']);
    }

    return ['name' => $name, 'price' => $input->price, 'stock' => $input->stock];
}

$method = $_SERVER['REQUEST_METHOD'];
if (!in_array($method, ['GET', 'POST', 'PUT', 'DELETE'], true)) {
    header('Allow: GET, POST, PUT, DELETE');
    respond(405, ['success' => false, 'message' => 'Phương thức không được hỗ trợ.']);
}

$id = null;
if ($method === 'PUT' || $method === 'DELETE') {
    $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1, 'max_range' => 2147483647],
    ]);
    if ($id === false) {
        respond(400, ['success' => false, 'message' => 'Mã sản phẩm trên URL phải là số nguyên dương hợp lệ.']);
    }
}

// Dữ liệu không hợp lệ bị từ chối trước khi kết nối và ghi cơ sở dữ liệu.
$product = ($method === 'POST' || $method === 'PUT') ? readProduct() : null;

try {
    $config = require __DIR__ . '/../config/database.php';
    $pdo = new PDO(
        "mysql:host={$config['host']};port={$config['port']};dbname={$config['name']};charset=utf8mb4",
        $config['user'],
        $config['password'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );

    if ($method === 'GET') {
        $products = $pdo->query('SELECT id, name, price, stock FROM products ORDER BY id')->fetchAll();
        respond(200, ['success' => true, 'data' => $products]);
    }

    if ($method === 'POST') {
        $statement = $pdo->prepare('INSERT INTO products (name, price, stock) VALUES (:name, :price, :stock)');
        $statement->execute($product);
        respond(201, [
            'success' => true,
            'message' => 'Đã thêm sản phẩm.',
            'data' => ['id' => (int) $pdo->lastInsertId()] + $product,
        ]);
    }

    if ($method === 'PUT') {
        $statement = $pdo->prepare('UPDATE products SET name = :name, price = :price, stock = :stock WHERE id = :id');
        $statement->execute($product + ['id' => $id]);
        // MySQL cũng trả rowCount = 0 nếu người dùng lưu lại dữ liệu không đổi.
        if ($statement->rowCount() === 0) {
            $exists = $pdo->prepare('SELECT id FROM products WHERE id = :id');
            $exists->execute(['id' => $id]);
            if ($exists->fetch() === false) {
                respond(404, ['success' => false, 'message' => 'Sản phẩm cần sửa không tồn tại.']);
            }
        }
        respond(200, ['success' => true, 'message' => 'Đã cập nhật sản phẩm.', 'data' => ['id' => $id] + $product]);
    }

    $statement = $pdo->prepare('DELETE FROM products WHERE id = :id');
    $statement->execute(['id' => $id]);
    if ($statement->rowCount() === 0) {
        respond(404, ['success' => false, 'message' => 'Sản phẩm cần xóa không tồn tại.']);
    }
    respond(200, ['success' => true, 'message' => 'Đã xóa sản phẩm.']);
} catch (Throwable $e) {
    error_log('[TH8 products API] ' . $e->getMessage());
    respond(500, ['success' => false, 'message' => 'Không thể xử lý dữ liệu. Hãy kiểm tra MySQL, nhập database.sql và kiểm tra cấu hình kết nối.']);
}
