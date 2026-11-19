const MAX_IMAGE_BYTES = 2 * 1024 * 1024;

Vue.createApp({
  data() {
    return {
      products: [],
      pagination: { page: 1, limit: 5, total: 0, total_pages: 1 },
      form: { name: '', price: '', stock: '' },
      editingId: null,
      currentImage: null,
      selectedImage: null,
      imagePreview: '',
      uploadPending: false,
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
    async request(url, method = 'GET', body = null) {
      const options = { method, headers: { Accept: 'application/json' }, cache: 'no-store' };
      if (body instanceof FormData) {
        options.body = body;
      } else if (body !== null) {
        options.headers['Content-Type'] = 'application/json';
        options.body = JSON.stringify(body);
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
    async loadProducts(page) {
      this.loading = true;
      try {
        const query = new URLSearchParams({ page, limit: this.pagination.limit });
        const result = await this.request('api/products.php?' + query);
        if (!Array.isArray(result.data)) throw new Error('Danh sách sản phẩm trả về không hợp lệ.');
        this.products = result.data;
        this.pagination = result.pagination;
        this.listLoaded = true;
      } finally {
        this.loading = false;
      }
    },
    async reloadProducts(page = this.pagination.page) {
      if (this.busy) return;
      this.errorMessage = '';
      this.successMessage = '';
      try {
        await this.loadProducts(page);
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
      this.currentImage = null;
      this.uploadPending = false;
      this.clearImageSelection();
    },
    clearImageSelection(clearInput = true) {
      if (this.imagePreview) URL.revokeObjectURL(this.imagePreview);
      this.imagePreview = '';
      this.selectedImage = null;
      if (clearInput && this.$refs.imageInput) this.$refs.imageInput.value = '';
    },
    selectImage(event) {
      const file = event.target.files[0];
      this.errorMessage = '';
      this.clearImageSelection(false);
      if (!file) return;

      const isJpeg = /\.jpe?g$/i.test(file.name) && (!file.type || file.type === 'image/jpeg');
      const isPng = /\.png$/i.test(file.name) && (!file.type || file.type === 'image/png');
      if (!isJpeg && !isPng) {
        event.target.value = '';
        this.errorMessage = 'Chỉ chấp nhận ảnh JPG, JPEG hoặc PNG.';
        return;
      }
      if (file.size === 0 || file.size > MAX_IMAGE_BYTES) {
        event.target.value = '';
        this.errorMessage = 'Ảnh phải có dung lượng lớn hơn 0 và tối đa 2 MB.';
        return;
      }
      this.selectedImage = file;
      this.imagePreview = URL.createObjectURL(file);
    },
    editProduct(product) {
      if (this.busy) return;
      this.errorMessage = '';
      this.successMessage = '';
      this.resetForm();
      this.editingId = product.id;
      this.currentImage = product.image;
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
    async refreshAfterChange(page = this.pagination.page) {
      try {
        await this.loadProducts(page);
      } catch (error) {
        this.errorMessage = [this.errorMessage, 'Chưa tải lại được danh sách. ' + error.message]
          .filter(Boolean).join(' ');
      }
    },
    async uploadImage(productId) {
      const body = new FormData();
      body.set('product_id', productId);
      body.set('image', this.selectedImage);
      const result = await this.request('api/upload.php', 'POST', body);
      this.currentImage = result.data.image;
      this.uploadPending = false;
      this.clearImageSelection();
      return result.message;
    },
    async retryUpload() {
      if (this.busy || !this.uploadPending || !this.selectedImage) return;
      this.errorMessage = '';
      this.successMessage = '';
      this.saving = true;
      try {
        this.successMessage = await this.uploadImage(this.editingId);
        await this.refreshAfterChange();
      } catch (error) {
        this.errorMessage = 'Chưa tải được ảnh. ' + error.message;
      } finally {
        this.saving = false;
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
      const isNew = this.editingId === null;
      const url = isNew ? 'api/products.php' : 'api/products.php?id=' + this.editingId;
      try {
        const result = await this.request(url, isNew ? 'POST' : 'PUT', product);
        this.successMessage = result.message;
        if (this.selectedImage) {
          this.editingId = result.data.id;
          try {
            this.successMessage += ' ' + await this.uploadImage(result.data.id);
          } catch (error) {
            this.uploadPending = true;
            this.errorMessage = 'Chưa tải được ảnh. ' + error.message + ' Bạn có thể bấm Thử tải ảnh lại.';
          }
        }
        if (!this.uploadPending || !this.selectedImage) this.resetForm();
        await this.refreshAfterChange(isNew ? 1 : this.pagination.page);
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
        const result = await this.request('api/products.php?id=' + product.id, 'DELETE');
        if (this.editingId === product.id) this.resetForm();
        this.successMessage = result.message;
        await this.refreshAfterChange();
      } catch (error) {
        this.errorMessage = error.message;
      } finally {
        this.deletingId = null;
      }
    }
  },
  mounted() {
    this.reloadProducts();
  },
  beforeUnmount() {
    this.clearImageSelection();
  }
}).mount('#app');
