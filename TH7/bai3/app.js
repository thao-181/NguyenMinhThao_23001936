const app = Vue.createApp({
  data() {
    return {
      cart: [
        { id: 1, ten: 'Bút bi', donGia: 5000, soLuong: 2 },
        { id: 2, ten: 'Quyển vở', donGia: 12000, soLuong: 3 }
      ],
      errorMessage: '',
      statusMessage: ''
    };
  },
  computed: {
    tongSoLuong() {
      return this.cart.reduce((total, item) => total + item.soLuong, 0);
    },
    tongTien() {
      return this.cart.reduce((total, item) => total + item.donGia * item.soLuong, 0);
    }
  },
  methods: {
    thanhToan() {
      if (this.cart.length === 0) return;
      const total = this.dinhDangTien(this.tongTien);
      if (!window.confirm(`Xác nhận thanh toán minh họa ${this.tongSoLuong} sản phẩm với tổng tiền ${total}?`)) return;
      this.cart = [];
      this.errorMessage = '';
      this.statusMessage = `Đã hoàn tất thanh toán minh họa ${total}. Không có giao dịch thu tiền thực tế.`;
    },
    dinhDangTien(value) {
      return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value);
    },
    capNhatSoLuong(id, soLuongMoi) {
      this.errorMessage = '';
      this.statusMessage = '';
      if (!Number.isInteger(id) || !this.cart.some((item) => item.id === id)) {
        this.errorMessage = 'Không tìm thấy sản phẩm trong giỏ hàng.';
        return;
      }
      if (!Number.isInteger(soLuongMoi) || soLuongMoi < 1 || soLuongMoi > 99) {
        this.errorMessage = 'Số lượng phải là số nguyên từ 1 đến 99.';
        return;
      }
      this.cart = this.cart.map((item) => item.id === id ? { ...item, soLuong: soLuongMoi } : item);
    },
    xoaSanPham(id) {
      this.errorMessage = '';
      this.statusMessage = '';
      if (!Number.isInteger(id) || !this.cart.some((item) => item.id === id)) {
        this.errorMessage = 'Không tìm thấy sản phẩm cần xóa.';
        return;
      }
      const product = this.cart.find((item) => item.id === id);
      this.cart = this.cart.filter((item) => item.id !== id);
      this.statusMessage = `Đã xóa ${product.ten} khỏi giỏ hàng.`;
    }
  }
});

app.component('dong-gio-hang', DongGioHang);
app.mount('#app');
