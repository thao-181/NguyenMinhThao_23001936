const { createApp } = Vue;

createApp({
  data() {
    return {
      pageStatus: 'Đang tải',
      keyword: '',
      selectedCategory: '',
      sortOrder: '',
      keywordMessage: '',
      formError: '',
      successMessage: '',
      showCategoryForm: false,
      newCategoryName: '',
      categoryError: '',
      nextId: 7,
      categories: ['Điện tử', 'Văn phòng phẩm', 'Gia dụng'],
      newProduct: { name: '', price: '', category: 'Điện tử', stock: '' },
      products: [
        { id: 1, name: 'Bàn phím không dây', price: 450000, category: 'Điện tử', stock: 12 },
        { id: 2, name: 'Chuột quang', price: 180000, category: 'Điện tử', stock: 0 },
        { id: 3, name: 'Sổ tay A5', price: 35000, category: 'Văn phòng phẩm', stock: 40 },
        { id: 4, name: 'Bút bi xanh', price: 8000, category: 'Văn phòng phẩm', stock: 100 },
        { id: 5, name: 'Bình giữ nhiệt', price: 250000, category: 'Gia dụng', stock: 18 },
        { id: 6, name: 'Đèn bàn', price: 320000, category: 'Gia dụng', stock: 0 }
      ]
    };
  },

  computed: {
    filteredProducts() {
      const keyword = this.keyword.trim().toLocaleLowerCase('vi-VN');
      // filter tạo mảng mới, vì vậy sort không thay đổi mảng products gốc.
      const result = this.products.filter((product) =>
        product.name.toLocaleLowerCase('vi-VN').includes(keyword) &&
        (!this.selectedCategory || product.category === this.selectedCategory)
      );
      if (this.sortOrder === 'asc') result.sort((a, b) => a.price - b.price);
      if (this.sortOrder === 'desc') result.sort((a, b) => b.price - a.price);
      return result;
    },
    totalProducts() {
      return this.products.length;
    },
    displayedCount() {
      return this.filteredProducts.length;
    },
    inventoryValue() {
      return this.filteredProducts.reduce((total, product) => total + product.price * product.stock, 0);
    }
  },

  watch: {
    keyword(value) {
      this.keywordMessage = Array.from(value).length === 1
        ? 'Bạn đang nhập một ký tự. Nhập thêm để tìm kiếm cụ thể hơn.'
        : '';
    }
  },

  methods: {
    toggleCategoryForm() {
      this.showCategoryForm = !this.showCategoryForm;
      this.newCategoryName = '';
      this.categoryError = '';
      if (this.showCategoryForm) {
        this.$nextTick(() => this.$refs.categoryInput.focus());
      }
    },
    addCategory() {
      const name = this.newCategoryName.trim().normalize('NFC');
      this.categoryError = '';
      if (!name) {
        this.categoryError = 'Vui lòng nhập tên danh mục.';
        return;
      }
      if (this.categories.some((category) =>
        category.normalize('NFC').toLocaleLowerCase('vi-VN') === name.toLocaleLowerCase('vi-VN')
      )) {
        this.categoryError = 'Danh mục này đã tồn tại.';
        return;
      }
      this.categories.push(name);
      this.newProduct.category = name;
      this.newCategoryName = '';
      this.showCategoryForm = false;
      this.successMessage = `Đã thêm danh mục “${name}”.`;
      this.$nextTick(() => this.$refs.productCategory.focus());
    },
    formatCurrency(value) {
      return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value);
    },
    addProduct() {
      this.formError = '';
      this.successMessage = '';
      const name = this.newProduct.name.trim();
      const price = Number(this.newProduct.price);
      const stock = Number(this.newProduct.stock);

      if (!name) {
        this.formError = 'Tên sản phẩm không được để trống.';
      } else if (!Number.isFinite(price) || price <= 0) {
        this.formError = 'Giá phải là số lớn hơn 0.';
      } else if (String(this.newProduct.stock).trim() === '' || !Number.isSafeInteger(stock) || stock < 0) {
        this.formError = 'Tồn kho phải là số nguyên không âm.';
      } else if (!this.categories.includes(this.newProduct.category)) {
        this.formError = 'Vui lòng chọn danh mục hợp lệ.';
      }
      if (this.formError) return;

      this.products.push({ id: this.nextId++, name, price, category: this.newProduct.category, stock });
      this.newProduct = { name: '', price: '', category: 'Điện tử', stock: '' };
      this.successMessage = `Đã thêm sản phẩm “${name}”. Sản phẩm hiển thị nếu phù hợp với bộ lọc hiện tại.`;
    },
    deleteProduct(product) {
      if (window.confirm(`Bạn có chắc muốn xóa sản phẩm “${product.name}”?`)) {
        this.products = this.products.filter((item) => item.id !== product.id);
        this.successMessage = `Đã xóa sản phẩm “${product.name}”.`;
      }
    },
    resetFilters() {
      this.keyword = '';
      this.selectedCategory = '';
      this.sortOrder = '';
    }
  },

  mounted() {
    this.pageStatus = 'Sẵn sàng';
  }
}).mount('#app');
