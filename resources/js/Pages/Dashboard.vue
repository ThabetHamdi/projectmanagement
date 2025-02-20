<template>
    <div class="min-h-screen bg-gray-50">
      <!-- Navigation Bar -->
      <nav class="bg-gradient-to-r from-blue-600 to-indigo-700 shadow-lg border-b border-white/10 backdrop-blur-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between h-16 items-center">
            <!-- Logo -->
            <Link :href="route('dashboard')" class="flex items-center space-x-2 group">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-7 w-7 text-white group-hover:text-blue-100 transition-colors"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"
                />
              </svg>
              <span class="text-xl font-semibold text-white group-hover:text-blue-100 transition-colors">
                Workstation
              </span>
            </Link>

            <!-- Navigation Links -->
            <div class="hidden sm:flex items-center space-x-8">
              <NavLink
                :href="route('dashboard')"
                :active="route().current('dashboard')"
                class="text-white hover:text-blue-100"
              >
                Dashboard
              </NavLink>
              <NavLink
                v-if="$page.props.auth.user.role === 'manager'"
                :href="route('projects.index')"
                :active="route().current('projects.index')"
                class="text-white hover:text-blue-100"
              >
                Projects
              </NavLink>
            </div>

            <!-- Right Section -->
            <div class="flex items-center space-x-4">
              <!-- Notifications -->
              <Dropdown align="right" width="48">
                <template #trigger>
                  <button class="relative text-white hover:text-blue-100 transition-colors">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002
                        6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165
                        6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6
                        0v1a3 3 0 11-6 0v-1m6 0H9"
                      />
                    </svg>
                    <span v-if="unreadCount > 0" class="absolute -top-2 -right-2 bg-red-500 text-xs text-white px-2 rounded-full">
                      {{ unreadCount }}
                    </span>
                  </button>
                </template>
                <template #content>
                  <div class="p-2">
                    <div v-if="notifications.length > 0" class="space-y-2">
                      <div
                        v-for="notification in notifications"
                        :key="notification.id"
                        @click="markAsRead(notification.id)"
                        class="p-3 hover:bg-gray-50 rounded-lg cursor-pointer transition-colors"
                      >
                        <div class="text-sm font-medium text-gray-800">{{ notification.data.title }}</div>
                        <div class="text-xs text-gray-500 mt-1">{{ notification.data.status }}</div>
                        <div class="text-xs text-gray-400 mt-1">{{ notification.created_at }}</div>
                      </div>
                    </div>
                    <div v-else class="text-sm text-gray-500 p-3">
                      No new notifications
                    </div>
                  </div>
                </template>
              </Dropdown>

              <!-- User Menu -->
              <Dropdown align="right" width="48">
                <template #trigger>
                  <div class="flex items-center space-x-2 group cursor-pointer">
                    <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center">
                      <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                        />
                      </svg>
                    </div>
                    <span class="text-white group-hover:text-blue-100 transition-colors">{{ $page.props.auth.user.name }}</span>
                  </div>
                </template>
                <template #content>
                  <DropdownLink :href="route('profile.edit')" class="hover:bg-gray-50">
                    Profile
                  </DropdownLink>
                  <DropdownLink :href="route('logout')" method="post" as="button" class="hover:bg-gray-50">
                    Log Out
                  </DropdownLink>
                </template>
              </Dropdown>
            </div>
          </div>
        </div>
      </nav>

      <!-- Main Content -->
      <AuthenticatedLayout>
        <template #header>
          <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>
        <div class="py-12 bg-gradient-to-br from-blue-50 to-purple-100">
          <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
              <div class="p-6 text-gray-900">
                <!-- Greeting and Date -->
                <div class="mb-8 text-center">
                  <h1 class="text-3xl font-bold text-gray-800">{{ greeting }}, {{ $page.props.auth.user.name }}!</h1>
                  <p class="text-lg text-gray-500">{{ currentDate }}</p>
                </div>

                <!-- Time Widget -->
                <div
                  class="mb-8 text-center bg-gradient-to-r from-blue-500 to-indigo-600 p-6 rounded-lg shadow-lg hover:shadow-2xl transition-shadow duration-300"
                >
                  <h3 class="text-lg font-semibold text-white mb-2">Current Time</h3>
                  <div class="text-4xl font-bold text-white">
                    {{ currentTime }}
                  </div>
                </div>

                <!-- Stats (3 Cards) -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                  <!-- Total Projects -->
                  <div
                    class="relative bg-white shadow-md p-6 rounded-lg text-center hover:shadow-xl transition-shadow duration-300"
                  >
                    <div class="absolute top-0 left-0 w-2 h-full bg-blue-600 rounded-l-lg"></div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Projects</h3>
                    <p class="text-3xl font-bold text-blue-600">{{ stats.totalProjects }}</p>
                  </div>
                  <!-- Total Tasks -->
                  <div
                    class="relative bg-white shadow-md p-6 rounded-lg text-center hover:shadow-xl transition-shadow duration-300"
                  >
                    <div class="absolute top-0 left-0 w-2 h-full bg-green-600 rounded-l-lg"></div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Tasks</h3>
                    <p class="text-3xl font-bold text-green-600">{{ stats.totalTasks }}</p>
                  </div>
                  <!-- Completed Tasks -->
                  <div
                    class="relative bg-white shadow-md p-6 rounded-lg text-center hover:shadow-xl transition-shadow duration-300"
                  >
                    <div class="absolute top-0 left-0 w-2 h-full bg-purple-600 rounded-l-lg"></div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Completed Tasks</h3>
                    <p class="text-3xl font-bold text-purple-600">{{ stats.completedTasks }}</p>
                  </div>
                </div>

                <!-- Quick Actions -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                  <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow p-6 text-center">
                    <Link :href="route('projects.create')" class="inline-flex items-center space-x-2 text-blue-600 hover:text-blue-800">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                      </svg>
                      <span class="font-semibold">New Project</span>
                    </Link>
                  </div>
                  <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow p-6 text-center">
                    <Link :href="route('tasks.create')" class="inline-flex items-center space-x-2 text-green-600 hover:text-green-800">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                      </svg>
                      <span class="font-semibold">New Task</span>
                    </Link>
                  </div>
                </div>

                <!-- Charts Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                  <!-- Doughnut Chart -->
                  <div class="bg-white shadow-md p-6 rounded-lg hover:shadow-xl transition-shadow duration-300">
                    <h3 class="text-lg font-semibold mb-4 text-gray-700">Task Distribution</h3>
                    <div class="h-48">
                      <canvas ref="chartCanvas"></canvas>
                    </div>
                  </div>
                  <!-- Bar Chart -->
                  <div class="bg-white shadow-md p-6 rounded-lg hover:shadow-xl transition-shadow duration-300">
                    <h3 class="text-lg font-semibold mb-4 text-gray-700">Project Progress</h3>
                    <div class="h-48">
                      <canvas ref="barChartCanvas"></canvas>
                    </div>
                  </div>
                </div>

                <!-- Line Chart -->
                <div class="bg-white shadow-md p-6 rounded-lg mb-8 hover:shadow-xl transition-shadow duration-300">
                  <h3 class="text-lg font-semibold mb-4 text-gray-700">
                    Task Trends (Last 7 Days)
                  </h3>
                  <div class="h-48">
                    <canvas ref="lineChartCanvas"></canvas>
                  </div>
                </div>

                <!-- Calendar -->
                <div class="bg-white shadow-md p-6 rounded-lg mb-8 hover:shadow-xl transition-shadow duration-300">
                  <h3 class="text-lg font-semibold mb-4 text-gray-700">Task Calendar</h3>
                  <FullCalendar :options="calendarOptions" />
                </div>
              </div>
            </div>
          </div>
        </div>
      </AuthenticatedLayout>
    </div>
  </template>

  <script setup>
  import { ref, computed, onMounted, defineComponent, h } from 'vue'
  import { Link, usePage } from '@inertiajs/vue3'
  import Dropdown from '@/Components/Dropdown.vue'
  import DropdownLink from '@/Components/DropdownLink.vue'
  import NavLink from '@/Components/NavLink.vue'
  import { Inertia } from '@inertiajs/inertia'
  import axios from 'axios'
  import { Chart, registerables } from 'chart.js'
  import FullCalendar from '@fullcalendar/vue3'
  import dayGridPlugin from '@fullcalendar/daygrid'
  import timeGridPlugin from '@fullcalendar/timegrid'

  // Initialize Chart.js
  Chart.register(...registerables)

  // Define props with default values to avoid errors if not provided
  const props = defineProps({
    stats: { type: Object, default: () => ({ completedTasks: 0, inProgressTasks: 0, pendingTasks: 0, totalProjects: 0, totalTasks: 0 }) },
    projectCompletion: { type: Array, default: () => [] },
    taskTrends: { type: Object, default: () => ({}) },
    calendarEvents: { type: Array, default: () => [] },
    users: { type: Array, default: () => [] }
  })

  // Refs for charts
  const chartCanvas = ref(null)
  const barChartCanvas = ref(null)
  const lineChartCanvas = ref(null)

  // Real-time clock and date
  const currentTime = ref(new Date().toLocaleTimeString())
  const currentDate = ref(
    new Date().toLocaleDateString('en-US', {
      weekday: 'long',
      month: 'long',
      day: 'numeric',
      year: 'numeric'
    })
  )

  // Greeting computed property
  const greeting = computed(() => {
    const hour = new Date().getHours()
    if (hour < 12) return 'Good morning'
    if (hour < 18) return 'Good afternoon'
    return 'Good evening'
  })

  // Update the clock and date every second
  onMounted(() => {
    setInterval(() => {
      const now = new Date()
      currentTime.value = now.toLocaleTimeString()
      currentDate.value = now.toLocaleDateString('en-US', {
        weekday: 'long',
        month: 'long',
        day: 'numeric',
        year: 'numeric'
      })
    }, 1000)

    // Doughnut Chart (Task Status)
    if (chartCanvas.value) {
      new Chart(chartCanvas.value, {
        type: 'doughnut',
        data: {
          labels: ['Terminées', 'En cours', 'À faire'],
          datasets: [
            {
              label: 'Task Status',
              data: [
                props.stats.completedTasks,
                props.stats.inProgressTasks,
                props.stats.pendingTasks,
              ],
              backgroundColor: ['#4caf50', '#ff9800', '#f44336'],
              borderWidth: 1,
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'bottom',
            },
          },
        },
      })
    }

    // Bar Chart (Project Completion)
    if (barChartCanvas.value) {
      new Chart(barChartCanvas.value, {
        type: 'bar',
        data: {
          labels: props.projectCompletion.map(project => project.name),
          datasets: [
            {
              label: "Completion Percentage",
              data: props.projectCompletion.map(project => project.completion),
              backgroundColor: '#3b82f6',
              borderWidth: 1,
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            y: {
              beginAtZero: true,
              max: 100,
            },
          },
        },
      })
    }

    // Line Chart (Task Trends)
    if (lineChartCanvas.value) {
      new Chart(lineChartCanvas.value, {
        type: 'line',
        data: {
          labels: Object.keys(props.taskTrends),
          datasets: [
            {
              label: 'Tasks Created',
              data: Object.values(props.taskTrends),
              borderColor: '#8b5cf6',
              fill: false,
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            y: {
              beginAtZero: true,
            },
          },
        },
      })
    }
  })

  // Calendar Configuration
  const calendarOptions = ref({
    plugins: [dayGridPlugin, timeGridPlugin],
    initialView: 'dayGridMonth',
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,timeGridWeek,timeGridDay',
    },
    events: props.calendarEvents,
  })

  // Get authenticated user from Inertia
  const user = usePage().props.auth.user

  // Notifications
  const unreadCount = ref(0)
  const notifications = ref([])

  const fetchNotifications = async () => {
    try {
      const response = await axios.get('/notifications')
      notifications.value = response.data.notifications
      unreadCount.value = response.data.unreadCount
    } catch (error) {
      console.error('Error fetching notifications:', error)
    }
  }

  const markAsRead = async (id) => {
    try {
      await axios.post(`/notifications/${id}/read`)
      fetchNotifications()
    } catch (error) {
      console.error('Error marking notification as read:', error)
    }
  }

  onMounted(() => {
    fetchNotifications()
  })

  // CRUD for Users
  const form = ref({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: 'user'
  })

  const editingUser = ref(null)

  const createUser = () => {
    Inertia.post('/admin/users', form.value, {
      preserveState: true,
      preserveScroll: true,
      onSuccess: () => {
        form.value = { name: '', email: '', password: '', password_confirmation: '', role: 'user' }
        Inertia.reload({ preserveScroll: true, only: ['users'] })
      }
    })
  }

  const editUser = (user) => {
    editingUser.value = { ...user }
  }

  const updateUser = () => {
    Inertia.put(`/admin/users/${editingUser.value.id}`, editingUser.value, {
      preserveState: true,
      preserveScroll: true,
      onSuccess: () => {
        editingUser.value = null
        Inertia.reload({ preserveScroll: true, only: ['users'] })
      }
    })
  }

  const deleteUser = (user) => {
    if (confirm('Are you sure you want to delete this user?')) {
      Inertia.delete(`/admin/users/${user.id}`, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
          Inertia.reload({ preserveScroll: true, only: ['users'] })
        }
      })
    }
  }

  const cancelEdit = () => {
    editingUser.value = null
  }

  // Compute dynamic badge classes for user role
  const roleBadgeClasses = (role) => {
    switch (role) {
      case 'admin':
        return 'bg-blue-100 text-blue-700'
      case 'manager':
        return 'bg-green-100 text-green-700'
      case 'developer':
        return 'bg-purple-100 text-purple-700'
      default:
        return 'bg-gray-100 text-gray-700'
    }
  }
  </script>

  <style scoped>
  .fade-enter-active,
  .fade-leave-active {
    transition: opacity 0.3s ease;
  }
  .fade-enter-from,
  .fade-leave-to {
    opacity: 0;
  }
  /* Optional: Subtle hover scaling for chart containers */
  .bg-white:hover canvas {
    transform: scale(1.01);
    transition: transform 0.3s ease-in-out;
  }
  </style>
