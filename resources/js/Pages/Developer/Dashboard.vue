<template>
  <AuthenticatedLayout>
    <Head title="Developer Dashboard" />

    <div class="developer-dashboard max-w-4xl mx-auto p-6">
      <h1 class="text-3xl font-bold mb-6">Developer Dashboard</h1>

      <!-- Loop through tasks assigned to the logged-in user -->
      <div
        v-for="task in tasks"
        :key="task.id"
        class="task-item border p-6 mb-8 rounded shadow-sm"
      >
        <!-- Task Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4">
          <h2 class="text-2xl font-semibold">{{ task.name }}</h2>
          <p class="text-sm text-gray-600 mt-2 md:mt-0">
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
            <span v-if="task.is_overdue" class="text-red-600 font-bold ml-2">
              Overdue!
            </span>
          </p>
        </div>

        <!-- Task Actions -->
        <div class="mb-4 space-x-3">
          <button
            :disabled="loadingTaskUpdates[task.id]"
            @click="updateTaskStatus(task, 'in_progress')"
            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition disabled:opacity-50"
          >
            <span v-if="loadingTaskUpdates[task.id]">Updating...</span>
            <span v-else>Mark as In Progress</span>
          </button>
          <button
            :disabled="loadingTaskUpdates[task.id]"
            @click="updateTaskStatus(task, 'completed')"
            class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition disabled:opacity-50"
          >
            <span v-if="loadingTaskUpdates[task.id]">Updating...</span>
            <span v-else>Mark as Completed</span>
          </button>
        </div>

        <!-- Comments Section -->
        <div class="mb-4">
          <h3 class="text-xl font-semibold mb-2">Comments</h3>
          <div
            v-for="comment in (task.comments || [])"
            :key="comment.id"
            class="border-b py-1 mb-1"
          >
            <p class="text-gray-700">
              {{ comment.comment }} -
              <span class="font-semibold">
                {{ comment.user?.name || 'Unknown' }}
              </span>
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
              :disabled="loadingComments[task.id]"
              class="mt-2 px-4 py-2 bg-indigo-500 text-white rounded hover:bg-indigo-600 transition disabled:opacity-50"
            >
              <span v-if="loadingComments[task.id]">Posting...</span>
              <span v-else>Add Comment</span>
            </button>
          </form>
        </div>

        <!-- Documents Section -->
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
            />
            <button
              type="submit"
              :disabled="uploadingDocuments[task.id]"
              class="px-4 py-2 bg-orange-500 text-white rounded hover:bg-orange-600 transition disabled:opacity-50"
            >
              <span v-if="uploadingDocuments[task.id]">Uploading...</span>
              <span v-else>Upload Document</span>
            </button>
          </form>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script>
import { Inertia } from '@inertiajs/inertia';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

export default {
  name: 'DeveloperDashboard',
  components: {
    AuthenticatedLayout,
    Head,
  },
  props: {
    tasks: {
      type: Array,
      required: true,
    },
  },
  data() {
    return {
      newComments: {},
      loadingTaskUpdates: {},
      loadingComments: {},
      uploadingDocuments: {},
    };
  },
  methods: {
    async updateTaskStatus(task, status) {
      this.$set(this.loadingTaskUpdates, task.id, true);
      try {
        await Inertia.put(`/tasks/${task.id}`, { status });
      } catch (error) {
        console.error('Error updating task status:', error);
      } finally {
        this.$set(this.loadingTaskUpdates, task.id, false);
      }
    },
    async addComment(task) {
      const commentText = this.newComments[task.id] || '';
      if (commentText.trim()) {
        this.$set(this.loadingComments, task.id, true);
        try {
          await Inertia.post(`/tasks/${task.id}/comments`, { comment: commentText });
          this.$set(this.newComments, task.id, '');
        } catch (error) {
          console.error('Error adding comment:', error);
        } finally {
          this.$set(this.loadingComments, task.id, false);
        }
      }
    },
    async uploadDocument(task) {
      const fileRef = this.$refs['file-' + task.id];
      const fileInput = Array.isArray(fileRef) ? fileRef[0] : fileRef;
      const file = fileInput && fileInput.files[0];
      if (file) {
        this.$set(this.uploadingDocuments, task.id, true);
        const formData = new FormData();
        formData.append('file', file);
        try {
          await Inertia.post(`/tasks/${task.id}/documents`, formData);
          fileInput.value = "";
        } catch (error) {
          console.error('Error uploading document:', error);
        } finally {
          this.$set(this.uploadingDocuments, task.id, false);
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
