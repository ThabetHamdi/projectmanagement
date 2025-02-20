<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';

const props = defineProps({
    notifications: Array
});

const markAsRead = (id) => {
    axios.post(`/notifications/${id}/read`).then(() => {
        location.reload();
    });
};
</script>

<template>
    <Head title="Notifications" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Notifications</h2>
        </template>

        <div class="py-6 max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">
                <ul>
                    <li v-for="notification in notifications" :key="notification.id" class="p-2 border-b flex justify-between">
                        <span>{{ notification.data.title }} - {{ notification.data.status }}</span>
                        <button @click="markAsRead(notification.id)" class="text-blue-500">Marquer comme lu</button>
                    </li>
                </ul>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
