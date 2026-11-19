# TH9 — Upload ảnh và phân trang sản phẩm

Bài thực hành tiếp nối CRUD sản phẩm của TH8 bằng PHP, MySQL/MariaDB và Vue 3. Cơ sở dữ liệu mặc định `th9_products` có 12 sản phẩm mẫu và cột `image VARCHAR(255) DEFAULT NULL`. Vue được lưu trong `vendor/`, chạy trực tiếp qua PHP.

## Chạy với XAMPP

1. Bật MySQL và Apache trong XAMPP. Mở `http://localhost/phpmyadmin`, chọn **Import** và nhập `TH9/database.sql` một lần để tạo dữ liệu mẫu.
2. Kiểm tra `config/database.php`. Mặc định dùng `127.0.0.1:3306`, tài khoản `root`, mật khẩu trống. Có thể đổi bằng các biến `TH9_DB_HOST`, `TH9_DB_PORT`, `TH9_DB_NAME`, `TH9_DB_USER` và `TH9_DB_PASSWORD`.
3. Tại thư mục `TH9`, chạy lệnh sau trong PowerShell và mở `http://localhost:8009/`:

   ```powershell
   C:\xampp\php\php.exe -d display_errors=0 -S localhost:8009 -t .
   ```

   Nếu đặt thư mục `TH9` trong `C:\xampp\htdocs`, có thể mở `http://localhost/TH9/` qua Apache.

PHP cần phiên bản 8.1 trở lên, cùng các extension `pdo_mysql`, `mbstring` và `fileinfo`. Tiến trình PHP cần quyền ghi vào `uploads/`. Để gửi được ảnh đúng mức tối đa, đặt `upload_max_filesize` ít nhất `2M` và `post_max_size` lớn hơn `2M` (ví dụ `8M`). Đặt `display_errors=Off` nếu dùng cấu hình PHP khác để cảnh báo của PHP được ghi vào log và phản hồi API luôn là JSON.

## API

| Phương thức | Đường dẫn | Dữ liệu và kết quả |
| --- | --- | --- |
| GET | `api/products.php?page=1&limit=5` | Mặc định trang 1, giới hạn cố định 5. Trả `success`, `data` gồm cả `image` và `pagination` với `page`, `limit`, `total`, `total_pages`. Nếu yêu cầu vượt trang cuối, trả trang cuối. |
| POST | `api/products.php` | Gửi JSON gồm `name`, `price`, `stock`. Trả mã sản phẩm mới trong `data.id`. |
| PUT | `api/products.php?id=1` | Gửi JSON như khi thêm. Ảnh hiện tại được giữ. |
| DELETE | `api/products.php?id=1` | Xóa sản phẩm và dọn ảnh do ứng dụng đã lưu cho sản phẩm. |
| POST | `api/upload.php` | Gửi `multipart/form-data` với `product_id` và `image` sau khi lưu sản phẩm. Trả `data.image` khi thành công. |

Giá và số lượng tồn là số nguyên JSON. API upload kiểm tra dung lượng và định dạng thực tế của file. Nếu upload thất bại, Vue giữ ảnh đã chọn để người dùng có thể bấm **Thử tải ảnh lại**. Tác vụ thử lại chỉ gửi ảnh cho sản phẩm đã lưu.

## Kiểm tra theo đề

1. Mở danh sách mẫu và kiểm tra 3 trang có lần lượt 5, 5 và 2 sản phẩm. Thử nút **Trước** và **Sau** ở cả hai đầu.
2. Thêm sản phẩm kèm ảnh PNG hợp lệ. Kiểm tra ảnh xem trước, trang 1 và ảnh thu nhỏ trong bảng sau khi lưu.
3. Thử chọn ảnh quá 2 MB hoặc tệp sai loại. Có thể gửi trực tiếp tới `api/upload.php` để kiểm tra phản hồi lỗi từ PHP.
4. Sửa sản phẩm có ảnh mà để trống ô chọn ảnh. Kiểm tra ảnh hiện tại vẫn hiển thị sau khi lưu.
5. Khi trang cuối còn một sản phẩm, xóa sản phẩm đó. Kiểm tra Vue tự hiển thị trang hợp lệ do API trả về. Khi đã xóa hết, dòng trạng thái hiển thị trang 1/1, tổng 0 và cả hai nút bị khóa.
