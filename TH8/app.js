// Câu 3: Vue gọi API PHP; dữ liệu sản phẩm được lưu trong MySQL.
Vue.createApp({
  data() {
    return {
      products: [],
      form: { name: '', price: '', stock: '' },
      editingId: null,
      loading: false,
      saving: false,
      deletingId: null,
      listLoaded: false,
      errorMessage: '',
      successMessage: ''
    };
  },
  computed: {
    busy() {
      return this.loading || this.saving || this.deletingId !== null;
    }
  },
  methods: {
    async request(method = 'GET', product = null, id = null) {
      const url = 'api/products.php' + (id === null ? '' : '?id=' + encodeURIComponent(id));
      const options = { method, headers: { Accept: 'application/json' }, cache: 'no-store' };
      if (product !== null) {
        options.headers['Content-Type'] = 'application/json';
        options.body = JSON.stringify(product);
      }

      let response;
      try {
        response = await fetch(url, options);
      } catch (error) {
        throw new Error('Không kết nối được máy chủ. Vui lòng kiểm tra PHP và thử lại.');
      }
      let result;
      try {
        result = await response.json();
      } catch (error) {
        throw new Error('Máy chủ chưa trả về JSON. Hãy mở trang qua máy chủ PHP theo README, không mở trực tiếp file HTML.');
      }
      if (!response.ok || result.success !== true) {
        throw new Error(result.message || 'Yêu cầu thất bại. Vui lòng thử lại.');
      }
      return result;
    },
    async loadProducts() {
      this.loading = true;
      try {
        const result = await this.request();
        if (!Array.isArray(result.data)) throw new Error('Danh sách sản phẩm trả về không hợp lệ.');
        this.products = result.data;
        this.listLoaded = true;
      } finally {
        this.loading = false;
      }
    },
    async reloadProducts() {
      if (this.busy) return;
      this.errorMessage = '';
      this.successMessage = '';
      try {
        await this.loadProducts();
      } catch (error) {
        this.errorMessage = error.message;
      }
    },
    formatCurrency(value) {
      return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value);
    },
    resetForm() {
      this.editingId = null;
      this.form = { name: '', price: '', stock: '' };
    },
    editProduct(product) {
      if (this.busy) return;
      this.errorMessage = '';
      this.successMessage = '';
      this.editingId = product.id;
      // Sao chép để việc nhập biểu mẫu không làm thay đổi bảng trước khi lưu.
      this.form = { name: product.name, price: product.price, stock: product.stock };
      this.$nextTick(() => this.$refs.nameInput.focus());
    },
    validateForm() {
      const name = this.form.name.trim();
      const price = Number(this.form.price);
      const stock = Number(this.form.stock);
      if (Array.from(name).length < 1 || Array.from(name).length > 100) {
        throw new Error('Tên sản phẩm phải có từ 1 đến 100 ký tự sau khi bỏ khoảng trắng hai đầu.');
      }
      if (String(this.form.price).trim() === '' || !Number.isInteger(price) || price <= 0 || price > 2147483647) {
        throw new Error('Đơn giá phải là số nguyên từ 1 đến 2.147.483.647 đồng.');
      }
      if (String(this.form.stock).trim() === '' || !Number.isInteger(stock) || stock < 0 || stock > 2147483647) {
        throw new Error('Số lượng tồn phải là số nguyên từ 0 đến 2.147.483.647.');
      }
      return { name, price, stock };
    },
    async refreshAfterChange(message) {
      this.successMessage = message;
      try {
        // Luôn gọi lại GET sau thao tác thành công, không tải lại trang.
        await this.loadProducts();
      } catch (error) {
        this.errorMessage = 'Thao tác đã được lưu, nhưng chưa tải lại được danh sách. ' + error.message;
      }
    },
    async saveProduct() {
      if (this.busy) return;
      this.errorMessage = '';
      this.successMessage = '';
      let product;
      try {
        product = this.validateForm();
      } catch (error) {
        this.errorMessage = error.message;
        return;
      }
      this.saving = true;
      try {
        const result = await this.request(this.editingId === null ? 'POST' : 'PUT', product, this.editingId);
        this.resetForm();
        await this.refreshAfterChange(result.message);
      } catch (error) {
        this.errorMessage = error.message;
      } finally {
        this.saving = false;
      }
    },
    async deleteProduct(product) {
      if (this.busy || !window.confirm(`Bạn có chắc muốn xóa sản phẩm “${product.name}”?`)) return;
      this.errorMessage = '';
      this.successMessage = '';
      this.deletingId = product.id;
      try {
        const result = await this.request('DELETE', null, product.id);
        if (this.editingId === product.id) this.resetForm();
        await this.refreshAfterChange(result.message);
      } catch (error) {
        this.errorMessage = error.message;
      } finally {
        this.deletingId = null;
      }
    }
  },
  mounted() {
    this.reloadProducts();
  }
}).mount('#app');
