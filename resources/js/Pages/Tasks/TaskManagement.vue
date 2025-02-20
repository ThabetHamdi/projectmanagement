<template>
    <div>
      <h1 class="text-2xl font-bold mb-4">Gestion des Tâches</h1>
      <button @click="showCreateModal = true" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md mb-4">
        + Nouvelle Tâche
      </button>
      <button @click="exportTasksToPdf" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md mb-4">
        Exporter en PDF
      </button>
      <table class="min-w-full bg-white">
        <thead>
          <tr>
            <th class="py-2 px-4 border-b">Titre</th>
            <th class="py-2 px-4 border-b">Description</th>
            <th class="py-2 px-4 border-b">Priorité</th>
            <th class="py-2 px-4 border-b">Statut</th>
            <th class="py-2 px-4 border-b">Assigné à</th>
            <th class="py-2 px-4 border-b">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="task in tasks" :key="task.id">
            <td class="py-2 px-4 border-b">{{ task.title }}</td>
            <td class="py-2 px-4 border-b">{{ task.description }}</td>
            <td class="py-2 px-4 border-b">{{ task.priority }}</td>
            <td class="py-2 px-4 border-b">{{ task.status }}</td>
            <td class="py-2 px-4 border-b">{{ task.assignedUser ? task.assignedUser.name : 'Non assigné' }}</td>
            <td class="py-2 px-4 border-b">
              <button @click="editTask(task)" class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded-md">
                Modifier
              </button>
              <button @click="deleteTask(task.id)" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded-md">
                Supprimer
              </button>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Create Task Modal -->
      <div v-if="showCreateModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
          <h3 class="text-xl font-semibold mb-4">Créer une Tâche</h3>
          <form @submit.prevent="createTask">
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700">Titre</label>
                <input v-model="newTask.title" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <textarea v-model="newTask.description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Priorité</label>
                <select v-model="newTask.priority" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                  <option value="low">Low</option>
                  <option value="medium">Medium</option>
                  <option value="high">High</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Statut</label>
                <select v-model="newTask.status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                  <option value="à faire">À faire</option>
                  <option value="en cours">En cours</option>
                  <option value="terminé">Terminé</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Assigné à</label>
                <select v-model="newTask.assigned_to" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                  <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }}</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Date d'échéance</label>
                <input v-model="newTask.due_date" type="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
              </div>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
              <button type="button" @click="showCreateModal = false" class="px-4 py-2 border rounded-md hover:bg-gray-50">
                Annuler
              </button>
              <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                Créer
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Edit Task Modal -->
      <div v-if="showEditModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
          <h3 class="text-xl font-semibold mb-4">Modifier la Tâche</h3>
          <form @submit.prevent="updateTask">
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700">Titre</label>
                <input v-model="currentTask.title" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <textarea v-model="currentTask.description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Priorité</label>
                <select v-model="currentTask.priority" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                  <option value="low">Low</option>
                  <option value="medium">Medium</option>
                  <option value="high">High</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Statut</label>
                <select v-model="currentTask.status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                  <option value="à faire">À faire</option>
                  <option value="en cours">En cours</option>
                  <option value="terminé">Terminé</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Assigné à</label>
                <select v-model="currentTask.assigned_to" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                  <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }}</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Date d'échéance</label>
                <input v-model="currentTask.due_date" type="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
              </div>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
              <button type="button" @click="showEditModal = false" class="px-4 py-2 border rounded-md hover:bg-gray-50">
                Annuler
              </button>
              <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                Sauvegarder
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </template>

  <script>
  import axios from 'axios';

  export default {
    data() {
      return {
        tasks: [],
        users: [],
        showCreateModal: false,
        showEditModal: false,
        newTask: {
          title: '',
          description: '',
          priority: 'low',
          status: 'à faire',
          assigned_to: null,
          due_date: null,
        },
        currentTask: null,
      };
    },
    methods: {
      async fetchTasks() {
        try {
          const response = await axios.get('/api/projects/1/tasks'); // Replace with your project ID
          this.tasks = response.data.tasks;
          this.users = response.data.users;
        } catch (error) {
          console.error('Error fetching tasks:', error);
        }
      },
      async createTask() {
        try {
          await axios.post('/api/projects/1/tasks', this.newTask); // Replace with your project ID
          this.fetchTasks();
          this.showCreateModal = false;
          this.resetNewTask();
        } catch (error) {
          console.error('Error creating task:', error);
        }
      },
      editTask(task) {
        this.currentTask = { ...task };
        this.showEditModal = true;
      },
      async updateTask() {
        try {
          await axios.put(`/api/tasks/${this.currentTask.id}`, this.currentTask);
          this.fetchTasks();
          this.showEditModal = false;
          this.currentTask = null;
        } catch (error) {
          console.error('Error updating task:', error);
        }
      },
      async deleteTask(taskId) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cette tâche ?')) {
          try {
            await axios.delete(`/api/tasks/${taskId}`);
            this.fetchTasks();
          } catch (error) {
            console.error('Error deleting task:', error);
          }
        }
      },
      async exportTasksToPdf() {
        try {
          const response = await axios.get('/api/projects/1/tasks/export-pdf', { responseType: 'blob' }); // Replace with your project ID
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', 'tasks.pdf');
          document.body.appendChild(link);
          link.click();
        } catch (error) {
          console.error('Error exporting tasks to PDF:', error);
        }
      },
      resetNewTask() {
        this.newTask = {
          title: '',
          description: '',
          priority: 'low',
          status: 'à faire',
          assigned_to: null,
          due_date: null,
        };
      },
    },
    mounted() {
      this.fetchTasks();
    },
  };
  </script>

  <style scoped>
  /* Add any necessary styles here */
  </style>
