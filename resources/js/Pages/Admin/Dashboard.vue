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
      <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <div
            v-for="(card, index) in statsCards"
            :key="index"
            class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow p-6"
          >
            <div class="flex justify-between items-center">
              <div>
                <p class="text-sm text-gray-500 uppercase tracking-wide">{{ card.title }}</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ card.value }}</p>
                <p class="text-xs text-gray-400 mt-2">{{ card.subtitle }}</p>
              </div>
              <div :class="card.iconBg" class="p-3 rounded-lg">
                <component :is="card.icon" class="w-8 h-8 text-white" />
              </div>
            </div>
          </div>
        </div>

        <!-- Users Table -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
          <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-800">Users</h2>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                  <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-100">
                <tr v-for="user in users" :key="user.id" class="hover:bg-gray-50 transition-colors">
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ user.name }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ user.email }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span :class="roleBadgeClasses(user.role)" class="px-2.5 py-1 rounded-full text-xs font-medium">
                      {{ user.role }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm space-x-2">
                    <button @click="editUser(user)" class="text-blue-600 hover:text-blue-800 transition-colors">
                      Edit
                    </button>
                    <button @click="deleteUser(user)" class="text-red-600 hover:text-red-800 transition-colors">
                      Delete
                    </button>
                  </td>
                </tr>
                <tr v-if="users.length === 0">
                  <td colspan="4" class="px-6 py-4 text-center text-gray-500 text-sm">
                    No users found
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Create User Form -->
        <div class="mt-8 bg-white rounded-xl shadow-sm p-6">
          <h2 class="text-lg font-semibold text-gray-800 mb-4">Create New User</h2>
          <form @submit.prevent="createUser" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                <input
                  v-model="form.name"
                  type="text"
                  required
                  class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input
                  v-model="form.email"
                  type="email"
                  required
                  class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input
                  v-model="form.password"
                  type="password"
                  required
                  class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                <input
                  v-model="form.password_confirmation"
                  type="password"
                  required
                  class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
              </div>
              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                <select
                  v-model="form.role"
                  required
                  class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                  <option value="admin">Admin</option>
                  <option value="manager">Manager</option>
                  <option value="developer">Developer</option>
                  <option value="user">User</option>
                </select>
              </div>
            </div>
            <div class="flex justify-end">
              <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg transition-colors shadow-sm">
                Create User
              </button>
            </div>
          </form>
        </div>
      </main>

      <!-- Edit User Modal -->
      <transition name="fade">
        <div v-if="editingUser" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50 flex items-center justify-center">
          <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Edit User</h2>
            <form @submit.prevent="updateUser" class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                <input
                  v-model="editingUser.name"
                  type="text"
                  required
                  class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input
                  v-model="editingUser.email"
                  type="email"
                  required
                  class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">New Password (optional)</label>
                <input
                  v-model="editingUser.password"
                  type="password"
                  class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                <select
                  v-model="editingUser.role"
                  required
                  class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                  <option value="admin">Admin</option>
                  <option value="manager">Manager</option>
                  <option value="developer">Developer</option>
                  <option value="user">User</option>
                </select>
              </div>
              <div class="flex justify-end space-x-3">
                <button
                  type="button"
                  @click="cancelEdit"
                  class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors shadow-sm"
                >
                  Save Changes
                </button>
              </div>
            </form>
          </div>
        </div>
      </transition>
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

  // Icons (example for 'admin', add others as needed)
  const icons = {
    admin: defineComponent({
      render: () =>
        h(
          'svg',
          { class: 'w-6 h-6', fill: 'none', viewBox: '0 0 24 24', stroke: 'currentColor' },
          [
            h('path', {
              'stroke-linecap': 'round',
              'stroke-linejoin': 'round',
              'stroke-width': '2',
              d: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'
            })
          ]
        )
    })
    // Add other role icons here if you want different icons per role
  }

  // Props
  const props = defineProps({
    users: Array // user list
  })

  // Reactive form data for creating a new user
  const form = ref({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: 'user'
  })

  // Editing user data
  const editingUser = ref(null)

  // Notifications
  const unreadCount = ref(0)
  const notifications = ref([])

  // Stats Cards (example data: adapt as needed)
  const statsCards = ref([
    {
      title: 'Total Admins',
      value: '5',
      subtitle: 'System-wide administrators',
      icon: icons.admin,       // Example: using the 'admin' icon above
      iconBg: 'bg-blue-500'   // background color for the icon container
    },
    {
      title: 'Active Projects',
      value: '12',
      subtitle: 'Currently in progress',
      icon: icons.admin,       // Reuse or define new icons
      iconBg: 'bg-green-500'
    },
    {
      title: 'Pending Tasks',
      value: '34',
      subtitle: 'Tasks yet to be done',
      icon: icons.admin,
      iconBg: 'bg-yellow-500'
    },
    {
      title: 'Completed Tasks',
      value: '89',
      subtitle: 'All-time completions',
      icon: icons.admin,
      iconBg: 'bg-purple-500'
    }
  ])

  // Fetch notifications on mount
  onMounted(() => {
    fetchNotifications()
  })

  // Get current user from Inertia
  const user = usePage().props.auth.user

  // Fetch notifications from server
  const fetchNotifications = async () => {
    try {
      const response = await axios.get('/notifications')
      notifications.value = response.data.notifications
      unreadCount.value = response.data.unreadCount
    } catch (error) {
      console.error('Error fetching notifications:', error)
    }
  }

  // Mark a notification as read
  const markAsRead = async (id) => {
    try {
      await axios.post(`/notifications/${id}/read`)
      fetchNotifications()
    } catch (error) {
      console.error('Error marking notification as read:', error)
    }
  }

  // Create a new user
  const createUser = () => {
    Inertia.post('/admin/users', form.value, {
      preserveState: true,
      preserveScroll: true,
      onSuccess: () => {
        // Reset form
        form.value = {
          name: '',
          email: '',
          password: '',
          password_confirmation: '',
          role: 'user'
        }
        // Reload user list only
        Inertia.reload({ preserveScroll: true, only: ['users'] })
      }
    })
  }

  // Edit user
  const editUser = (user) => {
    editingUser.value = { ...user }
  }

  // Update user
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

  // Delete user
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

  // Cancel editing
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
  </style>
