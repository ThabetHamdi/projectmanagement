<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';

const props = defineProps({
    project: Object,
    users: Array
});

const form = useForm({
    user_id: '',
    role: 'developer'
});

const submit = () => {
    form.post(`/projects/${props.project.id}/team`, {
        onSuccess: () => form.reset(),
    });
};

const removeMember = (userId) => {
    if (confirm("Voulez-vous vraiment retirer ce membre ?")) {
        router.delete(`/projects/${props.project.id}/team/${userId}`, {
            onSuccess: () => {
                // Remove member from the local list to reflect changes immediately
                props.project.team_members = props.project.team_members.filter(member => member.user.id !== userId);
            }
        });
    }
};
</script>

<template>
    <Head title="Gérer l'équipe" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-2xl text-gray-900 leading-tight">👥 Gérer l'équipe de {{ project.name }}</h2>
        </template>

        <div class="py-10 max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-lg p-6">
                <!-- Section d'ajout d'un membre -->
                <h3 class="text-xl font-semibold text-gray-800 mb-4">➕ Ajouter un membre</h3>
                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <label class="block text-gray-700 font-medium">Utilisateur</label>
                        <select v-model="form.user_id" class="mt-1 w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-400">
                            <option value="" disabled>-- Sélectionner un utilisateur --</option>
                            <option v-for="user in users" :key="user.id" :value="user.id">
                                {{ user.name }} ({{ user.email }})
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium">Rôle</label>
                        <select v-model="form.role" class="mt-1 w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-400">
                            <option value="developer">Développeur</option>
                            <option value="manager">Manager</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition duration-300">
                        Ajouter au projet
                    </button>
                </form>

                <!-- Section des membres de l'équipe -->
                <h3 class="text-xl font-semibold text-gray-800 mt-8">👤 Membres du projet</h3>
                <div v-if="project.team_members.length > 0" class="mt-4 bg-gray-100 p-4 rounded-lg">
                    <ul>
                        <li v-for="member in project.team_members" :key="member.id" class="flex justify-between items-center p-3 bg-white rounded-lg shadow mb-2">
                            <span class="text-gray-800 font-medium">
                                {{ member.user.name }} -
                                <span class="text-sm text-gray-600">{{ member.role }}</span>
                            </span>
                            <button @click="removeMember(member.user.id)" class="px-3 py-1 bg-red-500 text-white rounded-lg hover:bg-red-700 transition duration-300">
                                ❌ Retirer
                            </button>
                        </li>
                    </ul>
                </div>
                <p v-else class="text-gray-500 mt-4 text-center">Aucun membre dans ce projet.</p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
