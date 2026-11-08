// Nạp file này trước app.js để component sẵn sàng khi đăng ký.
const TheSanPham = {
  props: {
    ten: { type: String, required: true },
    gia: { type: Number, required: true },
    anh: { type: String, default: 'images/san-pham.svg' },
    moTa: { type: String, default: 'Thông tin sản phẩm đang được cập nhật.' },
    soLuongTon: { type: Number, default: 0 }
  },
  data() {
    // Mỗi instance có một trạng thái riêng, không dùng chung giữa các thẻ.
    return { hienMoTa: false, thongBaoMua: '' };
  },
  methods: {
    dinhDangGia(gia) {
      return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(gia);
    },
    muaHang() {
      if (this.soLuongTon <= 0) return;
      this.thongBaoMua = `Bạn đã chọn mua ${this.ten}. Đây là thao tác minh họa.`;
    }
  },
  template: `
    <article class="product-card">
      <div class="product-image">
        <img :src="anh" :alt="ten" width="400" height="280">
        <span class="badge" :class="soLuongTon > 0 ? 'available' : 'sold-out'">
          {{ soLuongTon > 0 ? 'Còn hàng' : 'Hết hàng' }}
        </span>
      </div>
      <div class="product-content">
        <p class="stock">{{ soLuongTon > 0 ? 'Còn ' + soLuongTon + ' sản phẩm' : 'Tạm thời hết hàng' }}</p>
        <h2>{{ ten }}</h2>
        <p class="price">{{ dinhDangGia(gia) }}</p>
        <button class="description-toggle" type="button" @click="hienMoTa = !hienMoTa" :aria-expanded="hienMoTa">
          {{ hienMoTa ? 'Ẩn mô tả' : 'Xem mô tả' }} <span aria-hidden="true">{{ hienMoTa ? '−' : '+' }}</span>
        </button>
        <p v-if="hienMoTa" class="description">{{ moTa }}</p>
        <button class="buy-button" type="button" :disabled="soLuongTon <= 0" @click="muaHang">Mua</button>
        <p v-if="thongBaoMua" class="purchase-message" role="status">{{ thongBaoMua }}</p>
      </div>
    </article>
  `
};
