<?php
declare(strict_types=1);

require __DIR__ . '/common.php';

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
    $id = positiveInteger($_GET['id'] ?? null, 2147483647);
    if ($id === false) {
        respond(400, ['success' => false, 'message' => 'Mã sản phẩm trên URL phải là số nguyên dương hợp lệ.']);
    }
}

if ($method === 'GET') {
    $page = positiveInteger($_GET['page'] ?? '1');
    $limit = positiveInteger($_GET['limit'] ?? '5');
    if ($page === false || $limit !== 5) {
        respond(400, ['success' => false, 'message' => 'page phải là số nguyên dương và limit phải bằng 5.']);
    }
}

// Dữ liệu không hợp lệ bị từ chối trước khi kết nối và ghi cơ sở dữ liệu.
$product = ($method === 'POST' || $method === 'PUT') ? readProduct() : null;

$pdo = null;
try {
    $pdo = connectDatabase();

    if ($method === 'GET') {
        // Đếm và đọc trang từ cùng một snapshot khi có yêu cầu ghi đồng thời.
        $pdo->exec('SET TRANSACTION ISOLATION LEVEL REPEATABLE READ');
        $pdo->beginTransaction();
        $total = (int) $pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();
        $totalPages = max(1, (int) ceil($total / $limit));
        $page = min($page, $totalPages);
        $statement = $pdo->prepare(
            'SELECT id, name, price, stock, image FROM products ORDER BY id DESC LIMIT :limit OFFSET :offset'
        );
        $statement->bindValue('limit', $limit, PDO::PARAM_INT);
        $statement->bindValue('offset', ($page - 1) * $limit, PDO::PARAM_INT);
        $statement->execute();
        $products = $statement->fetchAll();
        $pdo->commit();

        respond(200, [
            'success' => true,
            'data' => $products,
            'pagination' => ['page' => $page, 'limit' => $limit, 'total' => $total, 'total_pages' => $totalPages],
        ]);
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

    $pdo->beginTransaction();
    $lookup = $pdo->prepare('SELECT image FROM products WHERE id = :id FOR UPDATE');
    $lookup->execute(['id' => $id]);
    $existing = $lookup->fetch();
    if ($existing === false) {
        $pdo->rollBack();
        respond(404, ['success' => false, 'message' => 'Sản phẩm cần xóa không tồn tại.']);
    }
    $statement = $pdo->prepare('DELETE FROM products WHERE id = :id');
    $statement->execute(['id' => $id]);
    $pdo->commit();
    removeProductImage($existing['image']);
    respond(200, ['success' => true, 'message' => 'Đã xóa sản phẩm.']);
} catch (Throwable $e) {
    if ($pdo?->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('[TH9 products API] ' . $e->getMessage());
    respond(500, ['success' => false, 'message' => 'Không thể xử lý dữ liệu. Hãy kiểm tra MySQL, nhập database.sql và kiểm tra cấu hình kết nối.']);
}
