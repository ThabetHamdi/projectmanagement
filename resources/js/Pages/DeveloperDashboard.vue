<template>
    <AuthenticatedLayout>
      <Head title="Developer Dashboard" />

      <div class="developer-dashboard max-w-4xl mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6">Developer Dashboard</h1>

        <!-- Filter Section -->
        <div class="filters mb-8 flex space-x-4">
          <div>
            <label class="font-semibold mr-2">Filter by Status:</label>
            <select v-model="selectedStatus" class="border p-2 rounded">
              <option value="">All</option>
              <option value="en attente">en attente</option>
              <option value="en cours">en cours</option>
              <option value="terminé">terminé</option>
              <option value="in_progress">in_progress</option>
              <option value="completed">completed</option>
            </select>
          </div>
          <div>
            <label class="font-semibold mr-2">Filter by Project:</label>
            <select v-model="selectedProject" class="border p-2 rounded">
              <option value="">All</option>
              <option v-for="project in projects" :key="project.id" :value="project.id">
                {{ project.name }}
              </option>
            </select>
          </div>
        </div>

        <!-- Projects Section (Optional) -->
        <div class="projects mb-8">
          <h2 class="text-2xl font-bold mb-4">Projects</h2>
          <ul>
            <li v-for="project in projects" :key="project.id" class="mb-2">
              {{ project.name }}
            </li>
          </ul>
        </div>

        <!-- Tasks Section -->
        <div
          v-for="task in filteredTasks"
          :key="task.id"
          class="task-item border p-6 mb-8 rounded shadow-sm"
        >
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-semibold">{{ task.name }}</h2>
            <p class="text-sm text-gray-600">
              Status:
              <span
                :class="{
                  'text-yellow-600': task.status === 'en attente',
                  'text-blue-600': task.status === 'en cours' || task.status === 'in_progress',
                  'text-green-600': task.status === 'terminé' || task.status === 'completed'
                }"
              >
                {{ task.status }}
              </span>
              <span v-if="task.is_overdue" class="text-red-600 font-bold ml-2">Overdue!</span>
            </p>
          </div>

          <!-- Buttons to update task status -->
          <div class="mb-4 space-x-3">
            <button
              @click="updateTaskStatus(task, 'in_progress')"
              class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition"
            >
              Mark as In Progress
            </button>
            <button
              @click="updateTaskStatus(task, 'completed')"
              class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition"
            >
              Mark as Completed
            </button>
          </div>

          <!-- Comments Section -->
          <div class="mb-4">
            <h3 class="text-xl font-semibold mb-2">Comments</h3>
            <div
              v-for="comment in task.comments"
              :key="comment.id"
              class="border-b py-1 mb-1"
            >
              <p class="text-gray-700">
                {{ comment.comment }} -
                <span class="font-semibold">{{ comment.user?.name || 'Unknown' }}</span>
              </p>
            </div>
            <form @submit.prevent="addComment(task)" class="mt-4">
              <textarea
                v-model="newComments[task.id]"
                required
                placeholder="Add a comment"
                class="w-full border rounded p-2"
              ></textarea>
              <button
                type="submit"
                class="mt-2 px-4 py-2 bg-indigo-500 text-white rounded hover:bg-indigo-600 transition"
              >
                Add Comment
              </button>
            </form>
          </div>

          <!-- Document Section -->
          <div>
            <h3 class="text-xl font-semibold mb-2">Documents</h3>
            <div
              v-for="document in (task.documents || [])"
              :key="document.id"
              class="py-1"
            >
              <a
                :href="`/storage/${document.file_path}`"
                target="_blank"
                class="text-blue-600 hover:underline"
              >
                View Document
              </a>
            </div>
            <form @submit.prevent="uploadDocument(task)" class="mt-4">
              <input
                type="file"
                :ref="'file-' + task.id"
                class="mb-2"
                required
              >
              <button
                type="submit"
                class="px-4 py-2 bg-orange-500 text-white rounded hover:bg-orange-600 transition"
              >
                Upload Document
              </button>
            </form>
          </div>
        </div>
      </div>
    </AuthenticatedLayout>
  </template>

  <script>
  import { computed } from 'vue';
  import { Head, Link } from '@inertiajs/vue3';
  import { Inertia } from '@inertiajs/inertia';
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import Dropdown from '@/Components/Dropdown.vue';
  import DropdownLink from '@/Components/DropdownLink.vue';
  import NavLink from '@/Components/NavLink.vue';

  export default {
    name: 'DeveloperDashboard',
    components: {
      AuthenticatedLayout,
      Head,
      Link,
      Dropdown,
      DropdownLink,
      NavLink,
    },
    props: {
      tasks: {
        type: Array,
        required: true,
      },
      projects: {
        type: Array,
        required: true,
      },
      comments: {
        type: Array,
        required: true,
      },
    },
    data() {
      return {
        newComments: {},
        selectedStatus: '',
        selectedProject: '',
      };
    },
    computed: {
      // Merge global comments into each task based on matching task_id.
      tasksWithComments() {
        return this.tasks.map(task => ({
          ...task,
          comments: this.comments.filter(comment => comment.task_id === task.id)
        }));
      },
      // Filter tasks based on selected status and project.
      filteredTasks() {
        return this.tasksWithComments.filter(task => {
          const statusMatch = this.selectedStatus
            ? task.status === this.selectedStatus
            : true;
          const projectMatch = this.selectedProject
            ? task.project_id == this.selectedProject
            : true;
          return statusMatch && projectMatch;
        });
      },
    },
    methods: {
      async updateTaskStatus(task, status) {
        try {
          await Inertia.put(`/tasks/${task.id}`, { status });
        } catch (error) {
          console.error('Error updating task status:', error);
        }
      },
      async addComment(task) {
        const commentText = this.newComments[task.id] || '';
        if (commentText.trim()) {
          try {
            await Inertia.post(`/tasks/${task.id}/comments`, { comment: commentText });
            // Clear the comment input for that task after submission.
            this.$set(this.newComments, task.id, '');
          } catch (error) {
            console.error('Error adding comment:', error);
          }
        }
      },
      async uploadDocument(task) {
        const fileRef = this.$refs['file-' + task.id];
        const fileInput = Array.isArray(fileRef) ? fileRef[0] : fileRef;
        const file = fileInput && fileInput.files[0];
        if (file) {
          const formData = new FormData();
          formData.append('file', file);
          try {
            await Inertia.post(`/tasks/${task.id}/documents`, formData);
            // Clear the file input after upload.
            fileInput.value = '';
          } catch (error) {
            console.error('Error uploading document:', error);
          }
        }
      },
    },
  };
  </script>

  <style scoped>
  .developer-dashboard {
    padding: 20px;
  }
  .task-item {
    background-color: #fff;
  }
  </style>
