<template>
    <div>
      <div class="min-h-screen bg-gray-50">
        <!-- Navbar -->
        <nav class="bg-gradient-to-r from-blue-600 to-indigo-700 border-b border-white/10 shadow-lg backdrop-blur-sm">
          <!-- Primary Navigation Menu -->
          <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
              <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
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
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
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
                  <!-- Developer Dashboard link -->
                  <NavLink
                    v-if="$page.props.auth.user.role === 'developer'"
                    :href="route('developer.dashboard')"
                    :active="route().current('developer.dashboard')"
                    class="text-white hover:text-blue-100"
                  >
                    Developer Dashboard
                  </NavLink>
                  <!-- Admin Space link -->
                  <NavLink
                    v-if="$page.props.auth.user.role === 'admin'"
                    :href="route('admin.dashboard')"
                    :active="route().current('admin.dashboard')"
                    class="text-white hover:text-blue-100"
                  >
                    Admin Space
                  </NavLink>
                </div>
              </div>

              <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Notifications Dropdown -->
                <div class="ms-3 relative">
                  <Dropdown align="right" width="48">
                    <template #trigger>
                      <span class="inline-flex rounded-md">
                        <button
                          type="button"
                          class="inline-flex items-center p-2 text-white hover:text-blue-100 focus:outline-none transition-colors duration-200 relative"
                        >
                          <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-6 w-6"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                          >
                            <path
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                            />
                          </svg>
                          <!-- Badge for unread notifications -->
                          <span
                            v-if="unreadCount > 0"
                            class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-500 rounded-full transform translate-x-1/2 -translate-y-1/2"
                          >
                            {{ unreadCount }}
                          </span>
                        </button>
                      </span>
                    </template>

                    <template #content>
                      <div class="p-2">
                        <div v-if="notifications.length > 0">
                          <div
                            v-for="notification in notifications"
                            :key="notification.id"
                            class="p-3 hover:bg-gray-50 cursor-pointer transition-colors duration-200 rounded-lg"
                            @click="markAsRead(notification.id)"
                          >
                            <div class="text-sm text-gray-800 font-medium">
                              {{ notification.data.title }}
                            </div>
                            <div class="text-xs text-gray-600 mt-1">
                              {{ notification.data.status }}
                            </div>
                            <div class="text-xs text-gray-400 mt-1">
                              {{ notification.created_at }}
                            </div>
                          </div>
                        </div>
                        <div v-else class="text-sm text-gray-500 p-3">
                          No new notifications.
                        </div>
                      </div>
                    </template>
                  </Dropdown>
                </div>

                <!-- Settings Dropdown -->
                <div class="ms-3 relative">
                  <Dropdown align="right" width="48">
                    <template #trigger>
                      <span class="inline-flex rounded-md">
                        <button
                          type="button"
                          class="inline-flex items-center px-3 py-2 text-sm font-medium text-white hover:text-blue-100 focus:outline-none transition-colors duration-200"
                        >
                          {{ $page.props.auth.user.name }}
                          <svg
                            class="ms-2 -me-0.5 h-4 w-4"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                          >
                            <path
                              fill-rule="evenodd"
                              d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                              clip-rule="evenodd"
                            />
                          </svg>
                        </button>
                      </span>
                    </template>

                    <template #content>
                      <DropdownLink
                        :href="route('profile.edit')"
                        class="hover:bg-gray-50 text-gray-700"
                      >
                        Profile
                      </DropdownLink>
                      <DropdownLink
                        :href="route('logout')"
                        method="post"
                        as="button"
                        class="hover:bg-gray-50 text-gray-700"
                      >
                        Log Out
                      </DropdownLink>
                    </template>
                  </Dropdown>
                </div>
              </div>

              <!-- Hamburger -->
              <div class="-me-2 flex items-center sm:hidden">
                <button
                  @click="showingNavigationDropdown = !showingNavigationDropdown"
                  class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-blue-100 hover:bg-white/10 focus:outline-none transition-colors duration-200"
                >
                  <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path
                      :class="{
                        hidden: showingNavigationDropdown,
                        'inline-flex': !showingNavigationDropdown,
                      }"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M4 6h16M4 12h16M4 18h16"
                    />
                    <path
                      :class="{
                        hidden: !showingNavigationDropdown,
                        'inline-flex': showingNavigationDropdown,
                      }"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M6 18L18 6M6 6l12 12"
                    />
                  </svg>
                </button>
              </div>
            </div>
          </div>

          <!-- Responsive Navigation Menu -->
          <div
            :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
            class="sm:hidden bg-white/5"
          >
            <div class="pt-2 pb-3 space-y-1">
              <ResponsiveNavLink
                :href="route('dashboard')"
                :active="route().current('dashboard')"
                class="text-white hover:bg-white/10"
              >
                Dashboard
              </ResponsiveNavLink>
              <ResponsiveNavLink
                v-if="$page.props.auth.user.role === 'manager'"
                :href="route('projects.index')"
                :active="route().current('projects.index')"
                class="text-white hover:bg-white/10"
              >
                Projects
              </ResponsiveNavLink>
              <!-- Developer Dashboard link for mobile -->
              <ResponsiveNavLink
                v-if="$page.props.auth.user.role === 'developer'"
                :href="route('developer.dashboard')"
                :active="route().current('developer.dashboard')"
                class="text-white hover:bg-white/10"
              >
                Developer Dashboard
              </ResponsiveNavLink>
              <!-- Admin Space link for mobile -->
              <ResponsiveNavLink
                v-if="$page.props.auth.user.role === 'admin'"
                :href="route('admin.dashboard')"
                :active="route().current('admin.dashboard')"
                class="text-white hover:bg-white/10"
              >
                Admin Space
              </ResponsiveNavLink>
            </div>

            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-white/10">
              <div class="px-4">
                <div class="font-medium text-base text-white">
                  {{ $page.props.auth.user.name }}
                </div>
                <div class="font-medium text-sm text-blue-100">
                  {{ $page.props.auth.user.email }}
                </div>
              </div>

              <div class="mt-3 space-y-1">
                <ResponsiveNavLink
                  :href="route('profile.edit')"
                  class="text-white hover:bg-white/10"
                >
                  Profile
                </ResponsiveNavLink>
                <ResponsiveNavLink
                  :href="route('logout')"
                  method="post"
                  as="button"
                  class="text-white hover:bg-white/10"
                >
                  Log Out
                </ResponsiveNavLink>
              </div>
            </div>
          </div>
        </nav>

        <!-- Page Heading -->
        <header class="bg-white shadow-sm" v-if="$slots.header">
          <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <slot name="header" />
          </div>
        </header>

        <!-- Page Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
          <slot />
        </main>
      </div>
    </div>
  </template>

  <script setup>
  import { ref } from 'vue';
  import Dropdown from '@/Components/Dropdown.vue';
  import DropdownLink from '@/Components/DropdownLink.vue';
  import NavLink from '@/Components/NavLink.vue';
  import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
  import { Link, usePage } from '@inertiajs/vue3';
  import axios from 'axios';

  const showingNavigationDropdown = ref(false);
  const notifications = ref([]);
  const unreadCount = ref(0);

  // Fetch notifications
  const fetchNotifications = async () => {
    try {
      const response = await axios.get('/notifications');
      notifications.value = response.data.notifications;
      unreadCount.value = response.data.unreadCount;
    } catch (error) {
      console.error('Error fetching notifications:', error);
    }
  };

  // Mark a notification as read
  const markAsRead = async (id) => {
    try {
      await axios.post(`/notifications/${id}/read`);
      fetchNotifications(); // Refresh notifications
    } catch (error) {
      console.error('Error marking notification as read:', error);
    }
  };

  // Fetch notifications on component mount
  fetchNotifications();
  </script>

  <style scoped>
  /* Consistent transitions */
  a, button, .transition-colors {
    transition: all 0.2s ease-in-out;
  }

  /* Notification dropdown styling */
  .dropdown-content {
    @apply rounded-lg shadow-lg border border-gray-100;
  }

  /* Responsive menu hover states */
  .responsive-nav-link:hover {
    @apply bg-white/10 rounded-md;
  }
  </style>
