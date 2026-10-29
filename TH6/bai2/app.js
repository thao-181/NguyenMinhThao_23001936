const { createApp } = Vue;

createApp({
  data() {
    return {
      showtimes: [
        '09:30 · Thứ Bảy, 31/10',
        '14:15 · Thứ Bảy, 31/10',
        '19:45 · Thứ Bảy, 31/10',
        '21:30 · Thứ Bảy, 31/10'
      ],
      ticketTypes: [
        {
          value: 'regular',
          label: 'Vé thường',
          description: 'Ghế tiêu chuẩn',
          price: 70000
        },
        {
          value: 'vip',
          label: 'Vé VIP',
          description: 'Ghế rộng, vị trí đẹp',
          price: 100000
        }
      ],
      comboPrice: 45000,
      form: this.getDefaultForm(),
      confirmation: null
    };
  },

  computed: {
    selectedTicket() {
      return this.ticketTypes.find((ticket) => ticket.value === this.form.ticketType)
        || this.ticketTypes[0];
    },

    isQuantityValid() {
      return Number.isInteger(this.form.quantity)
        && this.form.quantity >= 1
        && this.form.quantity <= 10;
    },

    ticketSubtotal() {
      if (!this.isQuantityValid) return 0;
      return this.selectedTicket.price * this.form.quantity;
    },

    totalPrice() {
      return this.ticketSubtotal + (this.form.hasCombo ? this.comboPrice : 0);
    },

    canSubmit() {
      return this.form.customerName.trim() !== '' && this.isQuantityValid;
    }
  },

  methods: {
    getDefaultForm() {
      return {
        customerName: '',
        showtime: '19:45 · Thứ Bảy, 31/10',
        ticketType: 'regular',
        quantity: 1,
        hasCombo: false
      };
    },

    increaseQuantity() {
      const quantity = Number(this.form.quantity);
      this.form.quantity = Number.isInteger(quantity)
        ? Math.min(quantity + 1, 10)
        : 1;
    },

    decreaseQuantity() {
      const quantity = Number(this.form.quantity);
      this.form.quantity = Number.isInteger(quantity)
        ? Math.max(quantity - 1, 1)
        : 1;
    },

    resetForm() {
      this.form = this.getDefaultForm();
      this.confirmation = null;
    },

    bookTickets() {
      if (!this.canSubmit) return;

      this.confirmation = {
        customerName: this.form.customerName,
        showtime: this.form.showtime,
        ticketLabel: this.selectedTicket.label,
        quantity: this.form.quantity,
        hasCombo: this.form.hasCombo,
        total: this.totalPrice
      };
    },

    formatMoney(value) {
      return Number(value).toLocaleString('vi-VN') + 'đ';
    }
  }
}).mount('#app');
