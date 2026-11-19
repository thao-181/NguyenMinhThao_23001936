<?php
declare(strict_types=1);

require __DIR__ . '/common.php';

const MAX_IMAGE_BYTES = 2 * 1024 * 1024;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    respond(405, ['success' => false, 'message' => 'Phương thức không được hỗ trợ.']);
}

$contentType = strtolower(trim(explode(';', $_SERVER['CONTENT_TYPE'] ?? '')[0]));
if ($contentType !== 'multipart/form-data') {
    respond(415, ['success' => false, 'message' => 'Vui lòng gửi ảnh dạng multipart/form-data.']);
}

if ($_POST === [] && $_FILES === [] && (int) ($_SERVER['CONTENT_LENGTH'] ?? 0) > MAX_IMAGE_BYTES) {
    respond(413, ['success' => false, 'message' => 'Dung lượng ảnh tối đa là 2 MB.']);
}

$id = positiveInteger($_POST['product_id'] ?? null, 2147483647);
if ($id === false) {
    respond(400, ['success' => false, 'message' => 'Mã sản phẩm phải là số nguyên dương hợp lệ.']);
}

$file = $_FILES['image'] ?? [];
$uploadError = $file['error'] ?? UPLOAD_ERR_NO_FILE;
if ($uploadError === UPLOAD_ERR_INI_SIZE || $uploadError === UPLOAD_ERR_FORM_SIZE
    || (is_int($file['size'] ?? null) && $file['size'] > MAX_IMAGE_BYTES)) {
    respond(413, ['success' => false, 'message' => 'Dung lượng ảnh tối đa là 2 MB.']);
}
if ($uploadError !== UPLOAD_ERR_OK || $file['size'] < 1 || !is_uploaded_file($file['tmp_name'])) {
    respond(422, ['success' => false, 'message' => 'Không nhận được ảnh hợp lệ. Vui lòng chọn ảnh và thử lại.']);
}

$extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$expectedMime = match ($extension) {
    'jpg', 'jpeg' => 'image/jpeg',
    'png' => 'image/png',
    default => null,
};
$pdo = null;
$newImage = null;
try {
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    $imageInfo = @getimagesize($file['tmp_name']);
    if ($expectedMime === null || $mime !== $expectedMime || ($imageInfo['mime'] ?? null) !== $expectedMime) {
        respond(422, ['success' => false, 'message' => 'Chỉ chấp nhận ảnh JPG, JPEG hoặc PNG đúng định dạng.']);
    }

    $pdo = connectDatabase();
    $pdo->beginTransaction();
    $lookup = $pdo->prepare('SELECT image FROM products WHERE id = :id FOR UPDATE');
    $lookup->execute(['id' => $id]);
    $product = $lookup->fetch();
    if ($product === false) {
        $pdo->rollBack();
        respond(404, ['success' => false, 'message' => 'Sản phẩm cần tải ảnh không tồn tại.']);
    }

    $path = 'uploads/' . bin2hex(random_bytes(16)) . ($mime === 'image/png' ? '.png' : '.jpg');
    if (!move_uploaded_file($file['tmp_name'], __DIR__ . '/../' . $path)) {
        throw new RuntimeException('Không thể ghi file vào uploads.');
    }
    $newImage = $path;

    $update = $pdo->prepare('UPDATE products SET image = :image WHERE id = :id');
    $update->execute(['image' => $newImage, 'id' => $id]);
    $pdo->commit();
} catch (Throwable $e) {
    if ($pdo?->inTransaction()) {
        $pdo->rollBack();
    }
    removeProductImage($newImage);
    error_log('[TH9 upload API] ' . $e->getMessage());
    respond(500, ['success' => false, 'message' => 'Không thể lưu ảnh. Vui lòng thử lại.']);
}

removeProductImage($product['image']);
respond(200, ['success' => true, 'message' => 'Đã lưu ảnh sản phẩm.', 'data' => ['id' => $id, 'image' => $newImage]]);
