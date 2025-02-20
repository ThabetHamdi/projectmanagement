<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    project: Object,
    tasks: Array,
    users: Array
});

const form = useForm({
    title: '',
    description: '',
    priority: 'medium',
    status: 'à faire',
    assigned_to: '',
    due_date: ''
});

const submit = () => {
    form.post(`/projects/${props.project.id}/tasks`, {
        onSuccess: () => form.reset(),
    });
};

const deleteTask = (taskId) => {
    if (confirm("Voulez-vous supprimer cette tâche ?")) {
        router.delete(`/tasks/${taskId}`);
    }
};
</script>

<template>
    <Head title="Gérer les Tâches" />

    <AuthenticatedLayout>
        <template #header>
            <div class="header-gradient">
                <h2 class="page-title">
                    Gérer les Tâches de {{ project.name }}
                </h2>
            </div>
        </template>

        <div class="page-container">
            <div class="task-manager-card">
                <!-- Formulaire -->
                <div class="form-section">
                    <h3 class="form-title">➕ Nouvelle Tâche</h3>
                    <form @submit.prevent="submit" class="task-form">
                        <div class="form-grid">
                            <div class="input-group">
                                <label>Titre</label>
                                <input
                                    v-model="form.title"
                                    type="text"
                                    class="modern-input"
                                    required
                                />
                            </div>

                            <div class="input-group">
                                <label>Description</label>
                                <textarea
                                    v-model="form.description"
                                    class="modern-textarea"
                                ></textarea>
                            </div>

                            <div class="input-group">
                                <label>Priorité</label>
                                <select
                                    v-model="form.priority"
                                    class="modern-select"
                                >
                                    <option value="low">Basse</option>
                                    <option value="medium">Moyenne</option>
                                    <option value="high">Haute</option>
                                </select>
                            </div>

                            <div class="input-group">
                                <label>Assignation</label>
                                <select
                                    v-model="form.assigned_to"
                                    class="modern-select"
                                >
                                    <option value="">Non assigné</option>
                                    <option
                                        v-for="user in users"
                                        :key="user.id"
                                        :value="user.id"
                                    >
                                        {{ user.name }}
                                    </option>
                                </select>
                            </div>

                            <div class="input-group">
                                <label>Échéance</label>
                                <input
                                    v-model="form.due_date"
                                    type="date"
                                    class="modern-input"
                                />
                            </div>
                        </div>

                        <button
                            type="submit"
                            class="submit-button"
                        >
                            Créer la Tâche
                        </button>
                    </form>
                </div>

                <!-- Liste des tâches -->
                <div class="task-list-section">
                    <h3 class="section-title">📋 Liste des Tâches</h3>
                    <div class="task-grid">
                        <div v-for="task in tasks" :key="task.id" class="task-card">
                            <div class="task-header">
                                <span class="priority-badge" :class="task.priority">
                                    {{ task.priority }}
                                </span>
                                <span class="status-badge" :class="task.status">
                                    {{ task.status }}
                                </span>
                            </div>
                            <div class="task-content">
                                <h4 class="task-title">{{ task.title }}</h4>
                                <p class="task-description">{{ task.description }}</p>

                                <div class="task-meta">
                                    <div class="meta-item">
                                        <span class="meta-label">Assigné à :</span>
                                        <span class="meta-value">{{ task.assigned_user?.name || 'Non assigné' }}</span>
                                    </div>
                                    <div class="meta-item">
                                        <span class="meta-label">Échéance :</span>
                                        <span class="meta-value">{{ task.due_date }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="task-actions">
                                <Link :href="`/tasks/${task.id}`" class="action-button info">
                                    👁️ Détails
                                </Link>
                                <Link :href="`/tasks/${task.id}/edit`" class="action-button warning">
                                    ✏️ Modifier
                                </Link>
                                <button @click="deleteTask(task.id)" class="action-button danger">
                                    🗑️ Supprimer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.header-gradient {
    background: linear-gradient(135deg, #4f46e5 0%, #818cf8 100%);
    padding: 2rem;
    border-radius: 0 0 15px 15px;
}

.page-title {
    color: white;
    font-size: 1.75rem;
    font-weight: 600;
    margin: 0;
}

.page-container {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 0 1rem;
}

.task-manager-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    overflow: hidden;
}

.form-section {
    padding: 2rem;
    border-bottom: 1px solid #eee;
}

.form-title {
    color: #1a1a1a;
    font-size: 1.5rem;
    margin-bottom: 1.5rem;
}

.task-form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.input-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

label {
    font-weight: 500;
    color: #4a5568;
}

.modern-input, .modern-select, .modern-textarea {
    padding: 0.75rem;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.modern-input:focus, .modern-select:focus, .modern-textarea:focus {
    border-color: #818cf8;
    box-shadow: 0 0 0 3px rgba(129, 140, 248, 0.2);
}

.submit-button {
    align-self: flex-start;
    background: #4f46e5;
    color: white;
    padding: 0.75rem 2rem;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.submit-button:hover {
    background: #6366f1;
    transform: translateY(-1px);
}

.task-list-section {
    padding: 2rem;
}

.section-title {
    font-size: 1.5rem;
    color: #1a1a1a;
    margin-bottom: 1.5rem;
}

.task-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
}

.task-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    border: 1px solid #eee;
}

.task-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 15px rgba(0,0,0,0.1);
}

.task-header {
    display: flex;
    justify-content: space-between;
    padding: 1rem;
    border-bottom: 1px solid #eee;
}

.priority-badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-weight: 600;
    text-transform: uppercase;
}

.priority-badge.low { background: #d1fae5; color: #065f46; }
.priority-badge.medium { background: #fef3c7; color: #92400e; }
.priority-badge.high { background: #fee2e2; color: #991b1b; }

.status-badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-weight: 600;
}

.status-badge.à\ faire { background: #e5e7eb; color: #374151; }
.status-badge.en\ cours { background: #bfdbfe; color: #1e40af; }
.status-badge.terminé { background: #dcfce7; color: #166534; }

.task-content {
    padding: 1rem;
}

.task-title {
    font-size: 1.125rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #1e293b;
}

.task-description {
    color: #64748b;
    font-size: 0.875rem;
    margin-bottom: 1rem;
}

.task-meta {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.meta-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.meta-label {
    color: #94a3b8;
    font-size: 0.75rem;
}

.meta-value {
    color: #475569;
    font-weight: 500;
    font-size: 0.875rem;
}

.task-actions {
    display: flex;
    gap: 0.5rem;
    padding: 1rem;
    border-top: 1px solid #eee;
}

.action-button {
    flex: 1;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 500;
    transition: all 0.3s ease;
    text-align: center;
}

.action-button.info {
    background: #e0f2fe;
    color: #0369a1;
}

.action-button.warning {
    background: #fef9c3;
    color: #a16207;
}

.action-button.danger {
    background: #fee2e2;
    color: #b91c1c;
}

.action-button:hover {
    filter: brightness(95%);
    transform: translateY(-1px);
}
</style>
