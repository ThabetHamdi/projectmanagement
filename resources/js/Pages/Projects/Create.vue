<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    description: '',
    starting_date: '',
    ending_date: '',
});

const submit = () => {
    form.post('/projects', {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Créer un projet" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Créer un Projet</h2>
        </template>

        <div class="py-6 max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">
                <form @submit.prevent="submit">
                    <div class="mb-4">
                        <label class="block font-medium text-gray-700">Nom du projet</label>
                        <input v-model="form.name" type="text" class="w-full border-gray-300 rounded-lg p-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-gray-700">Description</label>
                        <textarea v-model="form.description" class="w-full border-gray-300 rounded-lg p-2"></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-gray-700">Date de début</label>
                        <input v-model="form.starting_date" type="date" class="w-full border-gray-300 rounded-lg p-2">
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-gray-700">Date de fin</label>
                        <input v-model="form.ending_date" type="date" class="w-full border-gray-300 rounded-lg p-2">
                    </div>

                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Créer</button>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
