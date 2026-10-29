const { createApp } = Vue;

createApp({
  data() {
    return {
      student: {
        id: '23001936',
        fullName: 'Nguyễn Minh Thao',
        className: 'K68A4',
        avatar: 'assets/anh.jpeg',
        averageScore: 8.4,
        isStudying: true,
        introduction: "Yêu thích lập trình giao diện, thiết kế trải nghiệm người dùng và luôn sẵn sàng học hỏi những công nghệ mới."
      },
      showIntro: true
    };
  },

  computed: {
    formattedScore() {
      const score = Number(this.student.averageScore) || 0;
      return score.toLocaleString('vi-VN', {
        minimumFractionDigits: 1,
        maximumFractionDigits: 1
      });
    },

    rank() {
      const score = Number(this.student.averageScore) || 0;

      if (score >= 8) return 'Giỏi';
      if (score >= 6.5) return 'Khá';
      if (score >= 5) return 'Trung bình';
      return 'Chưa đạt';
    },

    rankClass() {
      const score = Number(this.student.averageScore) || 0;

      if (score >= 8) return 'rank-excellent';
      if (score >= 6.5) return 'rank-good';
      if (score >= 5) return 'rank-average';
      return 'rank-fail';
    }
  }
}).mount('#app');
