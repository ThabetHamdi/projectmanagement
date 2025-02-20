<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
  project: {
    type: Object,
    required: true,
  },
});

// Create a form with initial values from the project prop,
// providing defaults to avoid potential errors if any field is missing.
const form = useForm({
  name: props.project.name || '',
  description: props.project.description || '',
  status: props.project.status || 'en attente',
  starting_date: props.project.starting_date || '',
  ending_date: props.project.ending_date || '',
});

const submit = () => {
  form.put(`/projects/${props.project.id}`, {
    preserveState: true,
    onSuccess: () => {
      // Optionally, you can reset certain fields here.
      // form.reset(); // If you want to reset the form on success.
    },
  });
};
</script>

<template>
  <Head title="Modifier un projet" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Modifier le Projet</h2>
    </template>

    <div class="py-6 max-w-4xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white shadow-sm rounded-lg p-6">
        <form @submit.prevent="submit">
          <!-- Project Name -->
          <div class="mb-4">
            <label class="block font-medium text-gray-700">Nom du projet</label>
            <input
              v-model="form.name"
              type="text"
              class="w-full border-gray-300 rounded-lg p-2"
              required
            >
            <div v-if="form.errors.name" class="text-red-500 text-xs mt-1">
              {{ form.errors.name }}
            </div>
          </div>

          <!-- Description -->
          <div class="mb-4">
            <label class="block font-medium text-gray-700">Description</label>
            <textarea
              v-model="form.description"
              class="w-full border-gray-300 rounded-lg p-2"
            ></textarea>
            <div v-if="form.errors.description" class="text-red-500 text-xs mt-1">
              {{ form.errors.description }}
            </div>
          </div>

          <!-- Status -->
          <div class="mb-4">
            <label class="block font-medium text-gray-700">Statut</label>
            <select
              v-model="form.status"
              class="w-full border-gray-300 rounded-lg p-2"
            >
              <option value="en attente">En attente</option>
              <option value="en cours">En cours</option>
              <option value="terminé">Terminé</option>
            </select>
            <div v-if="form.errors.status" class="text-red-500 text-xs mt-1">
              {{ form.errors.status }}
            </div>
          </div>

          <!-- Starting Date -->
          <div class="mb-4">
            <label class="block font-medium text-gray-700">Date de début</label>
            <input
              v-model="form.starting_date"
              type="date"
              class="w-full border-gray-300 rounded-lg p-2"
            >
            <div v-if="form.errors.starting_date" class="text-red-500 text-xs mt-1">
              {{ form.errors.starting_date }}
            </div>
          </div>

          <!-- Ending Date -->
          <div class="mb-4">
            <label class="block font-medium text-gray-700">Date de fin</label>
            <input
              v-model="form.ending_date"
              type="date"
              class="w-full border-gray-300 rounded-lg p-2"
            >
            <div v-if="form.errors.ending_date" class="text-red-500 text-xs mt-1">
              {{ form.errors.ending_date }}
            </div>
          </div>

          <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded">
            Mettre à jour
          </button>
        </form>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
