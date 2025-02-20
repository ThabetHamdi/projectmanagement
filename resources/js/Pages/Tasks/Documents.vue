<script setup>
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    task: Object
});

const form = useForm({
    file: null
});

const submit = () => {
    form.post(`/tasks/${props.task.id}/documents`, {
        onSuccess: () => {
            form.reset(); // Reset the form after successful upload
            alert('Fichier téléchargé avec succès.'); // Notify the user
        },
        onError: (errors) => {
            alert('Erreur lors du téléchargement du fichier.'); // Notify the user of errors
        },
    });
};
</script>

<template>
    <div>
        <form @submit.prevent="submit" enctype="multipart/form-data">
            <input
                type="file"
                @input="form.file = $event.target.files[0]"
                accept=".pdf,.jpg,.jpeg,.png,.docx" <!-- Allowed file types -->
                required
            />
            <button
                type="submit"
                class="ml-2 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition"
            >
                Télécharger
            </button>
        </form>

        <!-- Display validation errors -->
        <div v-if="form.errors.file" class="text-red-500 mt-2">
            {{ form.errors.file }}
        </div>
    </div>
</template>
