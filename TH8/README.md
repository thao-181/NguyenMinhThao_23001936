# TH8 — Thực hành PHP và Vue.js

Ứng dụng quản lý sản phẩm theo đề: MySQL, API PHP dùng PDO prepared statements và giao diện Vue 3. Dữ liệu được lưu trong MySQL; các thao tác không tải lại trang. Vue được lưu sẵn trong `vendor/`, không cần npm hoặc mạng Internet để chạy.

## Cấu trúc

```text
TH8/
├── database.sql          # Câu 1: bảng products và 3 sản phẩm mẫu
├── config/database.php   # Cấu hình kết nối MySQL
├── api/products.php      # Câu 2: API GET, POST, PUT, DELETE
├── index.html            # Câu 3: giao diện Vue, v-for và v-model
├── app.js                # Gọi API, thêm/sửa/xóa, thông báo lỗi
├── styles.css
└── vendor/               # Vue 3 và giấy phép MIT
```

## Chạy bài

Yêu cầu PHP 8.1 trở lên, các extension `pdo_mysql`, `mbstring` và MySQL/MariaDB (có sẵn trong XAMPP).

1. Mở XAMPP Control Panel, bật **MySQL** và **Apache**.
2. Truy cập `http://localhost/phpmyadmin`, chọn **Import**, chọn `TH8/database.sql` rồi nhập. File tự tạo cơ sở dữ liệu `th8_products` và bảng `products` cùng ba sản phẩm mẫu. Chỉ nhập một lần vào cơ sở dữ liệu mới.
3. Kiểm tra `config/database.php`: mặc định host `127.0.0.1`, port `3306`, database `th8_products`, user `root`, mật khẩu rỗng. Sửa nếu máy dùng cấu hình khác. Có thể cấu hình qua các biến `TH8_DB_HOST`, `TH8_DB_PORT`, `TH8_DB_NAME`, `TH8_DB_USER`, `TH8_DB_PASSWORD`.
4. Với thư mục bài hiện tại, mở PowerShell và chạy:

   ```powershell
   cd 'D:\NAM BON\web\TH\TH8'
   C:\xampp\php\php.exe -S localhost:8008 -t .
   ```

5. Mở **http://localhost:8008/**. Khi dùng máy chủ PHP này, chỉ cần MySQL của XAMPP đang chạy; Apache chỉ cần cho phpMyAdmin. Dừng máy chủ bằng `Ctrl+C`.

Cách khác: đặt thư mục `TH8` trong `C:\xampp\htdocs`, bật Apache/MySQL và mở `http://localhost/TH8/`.

Không mở bằng `file://` hoặc Live Server vì các cách này không thực thi PHP.

## API

Mọi phản hồi là JSON, có `success`. Lỗi có `message`; GET thành công có mảng `data`. POST/PUT gửi `Content-Type: application/json` và đối tượng `{ "name": "Bút bi", "price": 5000, "stock": 20 }`. `price`, `stock` là số nguyên JSON, không phải chuỗi.

| Phương thức | Đường dẫn | Kết quả |
| --- | --- | --- |
| GET | `api/products.php` | Danh sách sản phẩm |
| POST | `api/products.php` | Thêm sản phẩm, HTTP 201 |
| PUT | `api/products.php?id=1` | Sửa sản phẩm theo id |
| DELETE | `api/products.php?id=1` | Xóa sản phẩm theo id |

Tên được `trim` rồi kiểm tra 1–100 ký tự Unicode; giá là số nguyên dương; tồn kho là số nguyên không âm. Các số phải nằm trong giới hạn cột INT của MySQL (tối đa 2.147.483.647). PHP từ chối dữ liệu sai trước khi ghi; không chỉ dựa vào kiểm tra trên giao diện. ID không hợp lệ trả HTTP 400, JSON sai cú pháp trả 400, dữ liệu sai trả 422, sản phẩm cần sửa/xóa không tồn tại trả 404. Lưu lại sản phẩm không thay đổi vẫn thành công.

## Câu 4 — Kiểm tra trước khi nộp

1. Thêm tên, giá, tồn kho hợp lệ; tải lại trang và kiểm tra sản phẩm vẫn còn.
2. Bấm **Sửa**, đổi đơn giá, bấm **Lưu sản phẩm**: bảng cập nhật mà không tải lại trang. Bấm **Hủy sửa** để trở về chế độ thêm.
3. Gửi trực tiếp yêu cầu không hợp lệ trong PowerShell:

   ```powershell
   $body = @{ name = 'San pham loi'; price = 5000; stock = -1 } | ConvertTo-Json
   try {
       Invoke-RestMethod -Uri 'http://localhost:8008/api/products.php' -Method Post -ContentType 'application/json' -Body $body
   } catch {
       $_.ErrorDetails.Message
   }
   ```

   Kết quả mong đợi: HTTP 422, `success: false`, thông báo tồn kho không hợp lệ. Gọi GET hoặc kiểm tra phpMyAdmin để xác nhận số bản ghi không tăng.
4. Bấm **Xóa**, thử hủy xác nhận để kiểm tra sản phẩm còn nguyên; đồng ý xóa rồi tải lại trang để kiểm tra sản phẩm đã mất.
5. Thử tên rỗng hoặc quá 100 ký tự, giá bằng 0 hoặc số thập phân, tồn kho âm, sửa/xóa ID không tồn tại. API phải trả lỗi JSON, không ghi dữ liệu sai.

Vue 3.5.13 được phân phối theo giấy phép MIT; giấy phép đầy đủ nằm trong `vendor/LICENSE.vue`.
