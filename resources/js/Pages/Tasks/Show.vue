<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    task: Object
});

const commentForm = useForm({
    comment: ''
});

const documentForm = useForm({
    file: null
});

const submitComment = () => {
    commentForm.post(`/tasks/${props.task.id}/comments`, {
        onSuccess: () => commentForm.reset(),
    });
};

const submitDocument = () => {
    documentForm.post(`/tasks/${props.task.id}/documents`, {
        onSuccess: () => documentForm.reset(),
    });
};
</script>

<template>
    <Head title="Task Details" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ task.title }}</h2>
        </template>

        <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Task Details -->
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Description</label>
                            <p class="mt-1 text-gray-900">{{ task.description }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Status</label>
                                <p class="mt-1 text-gray-900 capitalize">{{ task.status }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Due Date</label>
                                <p class="mt-1 text-gray-900">{{ task.due_date }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Existing Comments -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Comments</h3>
                        <div v-if="task.comments && task.comments.length" class="space-y-4">
                            <div
                                v-for="comment in task.comments"
                                :key="comment.id"
                                class="p-4 bg-gray-50 rounded-lg"
                            >
                                <div class="flex items-center justify-between">
                                    <p class="font-medium text-gray-900">{{ comment.user?.name }}</p>
                                    <span class="text-sm text-gray-500">
                                        {{ new Date(comment.created_at).toLocaleString() }}
                                    </span>
                                </div>
                                <p class="mt-2 text-gray-700">{{ comment.comment }}</p>
                            </div>
                        </div>
                        <p v-else class="text-gray-500">No comments yet.</p>
                    </div>

                    <!-- Add Comment Form -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Add a Comment</h3>
                        <form @submit.prevent="submitComment">
                            <textarea
                                v-model="commentForm.comment"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                rows="3"
                                placeholder="Write your comment here..."
                                required
                            ></textarea>
                            <div class="mt-4">
                                <button
                                    type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring-2 focus:ring-blue-500 transition ease-in-out duration-150"
                                    :disabled="commentForm.processing"
                                >
                                    Post Comment
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Documents Section -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Upload Document</h3>
                        <form @submit.prevent="submitDocument" class="flex items-center gap-4">
                            <div class="flex-1">
                                <input
                                    type="file"
                                    @input="documentForm.file = $event.target.files[0]"
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                    required
                                />
                            </div>
                            <button
                                type="submit"
                                class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring-2 focus:ring-green-500 transition ease-in-out duration-150"
                                :disabled="documentForm.processing"
                            >
                                Upload File
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
