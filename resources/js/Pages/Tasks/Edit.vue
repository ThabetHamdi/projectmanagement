<template>
    <AuthenticatedLayout>
      <Head title="Edit Task" />

      <div class="task-edit">
        <h1>Edit Task</h1>
        <form @submit.prevent="submitForm">
          <div>
            <label for="title">Title:</label>
            <input type="text" v-model="task.title" id="title" required />
          </div>
          <div>
            <label for="description">Description:</label>
            <textarea v-model="task.description" id="description" required></textarea>
          </div>
          <div>
            <label for="due_date">Due Date:</label>
            <input type="date" v-model="task.due_date" id="due_date" required />
          </div>
          <div>
            <label for="status">Status:</label>
            <select v-model="task.status" id="status" required>
              <option value="à faire">À faire</option>
              <option value="en cours">En cours</option>
              <option value="terminé">Terminé</option>
            </select>
          </div>
          <button type="submit">Save</button>
        </form>
      </div>
    </AuthenticatedLayout>
  </template>

  <script>
  import { Inertia } from '@inertiajs/inertia';
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

  export default {
    name: 'EditTask',
    components: { AuthenticatedLayout },
    props: {
      taskData: {
        type: Object,
        required: true,
      },
    },
    data() {
      return {
        task: {
          title: '',
          description: '',
          due_date: '',
          status: 'à faire',
          id: null,
          project_id: null,
        },
      };
    },
    methods: {
      submitForm() {
        Inertia.put(`/tasks/${this.task.id}`, this.task)
          .then(() => {
            console.log('Task updated successfully');
            // Redirect to the tasks list for the project
            if (this.task.project_id) {
              Inertia.visit(`/projects/${this.task.project_id}/tasks`);
            }
          })
          .catch((error) => {
            console.error('Error updating task:', error);
          });
      },
    },
    mounted() {
      if (this.taskData) {
        this.task = { ...this.taskData };
      }
    },
  };
  </script>

  <style scoped>
  .task-edit {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
  }
  .task-edit h1 {
    text-align: center;
    margin-bottom: 20px;
  }
  .task-edit form div {
    margin-bottom: 15px;
  }
  .task-edit form label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
  }
  .task-edit form input,
  .task-edit form textarea,
  .task-edit form select {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
  }
  .task-edit form button {
    display: block;
    width: 100%;
    padding: 10px;
    background-color: #4caf50;
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
  }
  .task-edit form button:hover {
    background-color: #45a049;
  }
  </style>
