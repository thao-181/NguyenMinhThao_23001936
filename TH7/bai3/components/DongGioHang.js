const DongGioHang = {
  props: {
    id: { type: Number, required: true },
    ten: { type: String, required: true },
    donGia: { type: Number, required: true },
    soLuong: {
      type: Number,
      required: true,
      validator: (value) => Number.isInteger(value) && value >= 1 && value <= 99
    }
  },
  emits: {
    'doi-so-luong': (id, soLuongMoi) => Number.isInteger(id) && Number.isInteger(soLuongMoi) && soLuongMoi >= 1 && soLuongMoi <= 99,
    'yeu-cau-xoa': (id) => Number.isInteger(id)
  },
  computed: {
    thanhTien() {
      return this.donGia * this.soLuong;
    }
  },
  methods: {
    dinhDangTien(value) {
      return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value);
    },
    doiSoLuong(soLuongMoi) {
      if (!Number.isInteger(soLuongMoi) || soLuongMoi < 1 || soLuongMoi > 99) return;
      // Chỉ gửi yêu cầu; component cha quyết định cập nhật dữ liệu.
      this.$emit('doi-so-luong', this.id, soLuongMoi);
    }
  },
  template: `
    <tr>
      <th scope="row"><span class="product-id">#{{ id }}</span><span class="product-name">{{ ten }}</span></th>
      <td class="money">{{ dinhDangTien(donGia) }}</td>
      <td>
        <div class="quantity-control">
          <button type="button" @click="doiSoLuong(soLuong - 1)" :disabled="soLuong <= 1" :aria-label="'Giảm số lượng ' + ten">−</button>
          <span :aria-label="'Số lượng ' + ten">{{ soLuong }}</span>
          <button type="button" @click="doiSoLuong(soLuong + 1)" :disabled="soLuong >= 99" :aria-label="'Tăng số lượng ' + ten">+</button>
        </div>
      </td>
      <td class="money subtotal">{{ dinhDangTien(thanhTien) }}</td>
      <td class="action"><button class="delete-button" type="button" @click="$emit('yeu-cau-xoa', id)" :aria-label="'Xóa ' + ten">Xóa</button></td>
    </tr>
  `
};
