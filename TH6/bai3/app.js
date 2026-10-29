const { createApp } = Vue;

createApp({
  data() {
    return {
      newTaskName: '',
      currentFilter: 'all',
      nextId: 5,
      showInputError: false,
      filterOptions: [
        { value: 'all', label: 'Tất cả' },
        { value: 'active', label: 'Chưa hoàn thành' },
        { value: 'completed', label: 'Đã hoàn thành' }
      ],
      tasks: [
        { id: 1, name: 'Hoàn thiện bài tập VueJS', completed: false },
        { id: 2, name: 'Đọc 20 trang sách', completed: true },
        { id: 3, name: 'Lên kế hoạch cho tuần mới', completed: false },
        { id: 4, name: 'Tập thể dục 30 phút', completed: false }
      ]
    };
  },

  computed: {
    totalTasks() {
      return this.tasks.length;
    },

    remainingTasks() {
      return this.tasks.filter((task) => !task.completed).length;
    },

    completedTasks() {
      return this.tasks.filter((task) => task.completed).length;
    },

    filteredTasks() {
      if (this.currentFilter === 'active') {
        return this.tasks.filter((task) => !task.completed);
      }

      if (this.currentFilter === 'completed') {
        return this.tasks.filter((task) => task.completed);
      }

      return this.tasks;
    },

    canAddTask() {
      return this.newTaskName.trim().length > 0;
    },

    completionPercent() {
      if (this.totalTasks === 0) return 0;
      return Math.round((this.completedTasks / this.totalTasks) * 100);
    },

    progressStyle() {
      return {
        background: `conic-gradient(#4067e8 ${this.completionPercent}%, #e8ebf2 0)`
      };
    },

    progressText() {
      if (this.totalTasks === 0) return 'Chưa có công việc nào';
      if (this.remainingTasks === 0) return 'Tuyệt vời! Bạn đã hoàn thành mọi việc';
      return `Còn ${this.remainingTasks} việc cần hoàn thành`;
    },

    activeFilterLabel() {
      const activeOption = this.filterOptions.find(
        (option) => option.value === this.currentFilter
      );
      return activeOption ? activeOption.label : 'Tất cả';
    }
  },

  methods: {
    addTask() {
      const taskName = this.newTaskName.trim();

      if (!taskName) {
        this.showInputError = true;
        return;
      }

      this.tasks.unshift({
        id: this.nextId++,
        name: taskName,
        completed: false
      });

      this.newTaskName = '';
      this.showInputError = false;
      this.currentFilter = 'all';
      this.$nextTick(() => this.$refs.taskInput.focus());
    },

    deleteTask(taskId) {
      this.tasks = this.tasks.filter((task) => task.id !== taskId);
    },

    clearCompleted() {
      this.tasks = this.tasks.filter((task) => !task.completed);
    },

    filterCount(filter) {
      if (filter === 'active') return this.remainingTasks;
      if (filter === 'completed') return this.completedTasks;
      return this.totalTasks;
    }
  }
}).mount('#app');
