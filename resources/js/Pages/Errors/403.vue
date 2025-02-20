<template>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="bg-white p-6 rounded-lg shadow-lg text-center">
            <h1 class="text-3xl font-bold text-red-500">403 - Accès Interdit</h1>
            <p class="mt-2 text-gray-600">Vous n'avez pas la permission d'accéder à cette page.</p>
            <Link href="/" class="mt-4 inline-block px-4 py-2 bg-blue-500 text-white rounded">Retourner à l'accueil</Link>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>
