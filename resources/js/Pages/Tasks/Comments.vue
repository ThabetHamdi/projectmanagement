<script setup>
import { useForm, router } from '@inertiajs/vue3';

const props = defineProps({
    task: Object, // The task object with comments
});

const form = useForm({
    comment: '', // Form field for the new comment
});

// Submit the form to post a new comment
const submit = () => {
    form.post(`/tasks/${props.task.id}/comments`, {
        onSuccess: () => form.reset(), // Reset the form after successful submission
    });
};

// Delete a comment
const deleteComment = (commentId) => {
    if (confirm("Voulez-vous supprimer ce commentaire ?")) {
        router.delete(`/comments/${commentId}`);
    }
};
</script>

<template>
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold">Commentaires</h3>

        <!-- Form to add a new comment -->
        <form @submit.prevent="submit" class="mt-2">
            <textarea
                v-model="form.comment"
                class="w-full border rounded p-2"
                placeholder="Ajouter un commentaire..."
                required
            ></textarea>
            <button
                type="submit"
                class="mt-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-200"
            >
                Envoyer
            </button>
        </form>

        <!-- List of comments -->
        <ul class="mt-4">
            <li
                v-for="comment in task.comments"
                :key="comment.id"
                class="p-2 border-b flex justify-between items-center"
            >
                <span class="text-gray-700">
                    <strong>{{ comment.user.name }}:</strong> {{ comment.comment }}
                </span>
                <button
                    @click="deleteComment(comment.id)"
                    class="text-red-500 hover:text-red-700 transition duration-200"
                >
                    Supprimer
                </button>
            </li>
        </ul>
    </div>
</template>
