const app = Vue.createApp({
  data() {
    return {
      products: [
        { id: 1, ten: 'Tai nghe không dây', gia: 790000, anh: 'images/tai-nghe.svg', moTa: 'Tai nghe chụp tai với đệm mềm, kết nối Bluetooth và thời lượng pin lên đến 30 giờ. Phù hợp nghe nhạc và học tập mỗi ngày.', soLuongTon: 12 },
        { id: 2, ten: 'Bàn phím cơ', gia: 1250000, anh: 'images/ban-phim.svg', moTa: 'Bàn phím cơ bố cục gọn gàng, phím bấm êm và kết nối USB-C. Thiết kế dành cho góc học tập và làm việc.', soLuongTon: 8 },
        { id: 3, ten: 'Chuột không dây', gia: 350000, anh: 'images/chuot.svg', moTa: 'Chuột không dây nhỏ gọn, cảm biến quang học và nút bấm nhẹ. Dễ mang theo khi đi học hoặc làm việc.', soLuongTon: 0 },
        { id: 4, ten: 'Đèn bàn học', gia: 450000, anh: 'images/den-ban.svg', moTa: 'Đèn bàn LED có thể điều chỉnh góc chiếu, ánh sáng dịu và chân đế chắc chắn. Thích hợp cho bàn học và bàn làm việc.', soLuongTon: 15 },
        { id: 5, ten: 'máy ảnh', gia: 500000, anh: 'images/san-pham.svg', moTa: 'Máy ảnh số chất lượng cao, cảm biến lớn và ống kính đa dạng. Lý tưởng cho những người yêu thích chụp ảnh.', soLuongTon: 3 }
      ]
    };
  }
});

// Đăng ký component sau khi nạp components/TheSanPham.js và trước mount.
app.component('the-san-pham', TheSanPham);
app.mount('#app');
