<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    projects: Array
});

const searchQuery = ref('');
const currentPage = ref(1);
const itemsPerPage = 5;

// 🔍 Filtrer les projets en fonction du texte recherché
const filteredProjects = computed(() => {
    return props.projects.filter(project =>
        project.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

// 📄 Pagination : Découpe les projets en pages
const paginatedProjects = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    return filteredProjects.value.slice(start, start + itemsPerPage);
});

// 🔄 Nombre total de pages
const totalPages = computed(() => Math.ceil(filteredProjects.value.length / itemsPerPage));

const deleteProject = (id) => {
    if (confirm("Voulez-vous vraiment supprimer ce projet ?")) {
        router.delete(`/projects/${id}`);
    }
};

const goToPage = (page) => {
    if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page;
    }
};
</script>

<template>
    <Head title="Projets" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">📌 Liste des Projets</h2>
        </template>

        <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- 🔍 Barre de recherche -->
            <div class="flex justify-between mb-6">
                <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Rechercher un projet..."
                    class="w-1/3 px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
                <Link href="/projects/create" class="px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-700 transition">
                    ➕ Créer un projet
                </Link>
            </div>

            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <template v-if="paginatedProjects.length > 0">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-200 text-gray-700 uppercase text-sm">
                                <th class="p-4 text-left">Nom</th>
                                <th class="p-4 text-left">Statut</th>
                                <th class="p-4 text-left">Manager</th>
                                <th class="p-4 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="project in paginatedProjects" :key="project.id" class="border-t hover:bg-gray-50 transition duration-200">
                                <td class="p-4 font-semibold text-gray-800">{{ project.name }}</td>
                                <td class="p-4">
                                    <span v-if="project.status === 'en attente'" class="px-3 py-1 text-sm bg-yellow-100 text-yellow-800 rounded-full">🟡 En attente</span>
                                    <span v-else-if="project.status === 'en cours'" class="px-3 py-1 text-sm bg-blue-100 text-blue-800 rounded-full">🔵 En cours</span>
                                    <span v-else class="px-3 py-1 text-sm bg-green-100 text-green-800 rounded-full">✅ Terminé</span>
                                </td>
                                <td class="p-4 text-gray-800">{{ project.manager?.name ?? 'Non attribué' }}</td>
                                <td class="p-4 flex space-x-2">
                                    <Link :href="`/projects/${project.id}/edit`" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
                                        ✏️ Modifier
                                    </Link>
                                    <Link :href="`/projects/${project.id}/tasks`" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition">
                                        📋 Tâches
                                    </Link>
                                    <Link :href="`/projects/${project.id}/team`" class="px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition">
                                        👥 Équipe
                                    </Link>
                                    <button @click="deleteProject(project.id)" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                                        🗑️ Supprimer
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </template>
                <template v-else>
                    <p class="text-center text-gray-500 py-8">Aucun projet disponible.</p>
                </template>
            </div>

            <!-- 📄 Pagination -->
            <div class="flex justify-center mt-6 space-x-2">
                <button
                    @click="goToPage(currentPage - 1)"
                    :disabled="currentPage === 1"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    ◀️ Précédent
                </button>
                <span class="px-4 py-2 bg-gray-100 text-gray-800 rounded-lg">
                    Page {{ currentPage }} sur {{ totalPages }}
                </span>
                <button
                    @click="goToPage(currentPage + 1)"
                    :disabled="currentPage === totalPages"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    Suivant ▶️
                </button>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
