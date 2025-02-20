<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
  canLogin: Boolean,
  canRegister: Boolean,
});
</script>

<template>
  <Head title="Welcome to Workstation" />

  <div class="min-h-screen flex flex-col bg-white">
    <!-- Navbar -->
    <nav class="border-b border-gray-100 bg-white/80 backdrop-blur-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
          <!-- Logo -->
          <Link :href="route('home')" class="flex items-center space-x-2 group">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-7 w-7 text-blue-600 group-hover:text-blue-700 transition-colors"
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
            <span class="text-xl font-semibold text-gray-800 group-hover:text-blue-600 transition-colors">
              Workstation
            </span>
          </Link>

          <!-- Right-side Login/Register -->
          <div class="flex items-center space-x-4">
            <template v-if="canLogin">
              <template v-if="$page.props.auth.user">
                <Link
                  :href="route('dashboard')"
                  class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors"
                >
                  Dashboard
                </Link>
              </template>
              <template v-else>
                <Link
                  :href="route('login')"
                  class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors"
                >
                  Log in
                </Link>
                <Link
                  v-if="canRegister"
                  :href="route('register')"
                  class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors"
                >
                  Get Started
                </Link>
              </template>
            </template>
          </div>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow">
      <!-- Hero Section -->
      <section class="py-20 md:py-28">
        <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
          <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold text-gray-900 mb-6 leading-tight">
            Streamline Your Team's <span class="text-blue-600">Productivity</span>
          </h1>
          <p class="text-xl text-gray-600 max-w-2xl mx-auto mb-8">
            Collaborate, manage projects, and deliver results faster with our all-in-one workspace platform.
          </p>
          <div class="mt-8">
            <Link
              :href="route('register')"
              class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg text-base font-medium shadow-lg hover:shadow-xl transition-all"
            >
              Start Free Trial
            </Link>
            <p class="mt-4 text-sm text-gray-500">
              14-day free trial • No credit card required
            </p>
          </div>
        </div>
      </section>

      <!-- Features Section -->
      <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="grid md:grid-cols-3 gap-12">
            <!-- Feature 1 -->
            <div class="bg-white p-8 rounded-xl shadow-sm hover:shadow-md transition-shadow">
              <div class="w-14 h-14 bg-blue-50 rounded-lg flex items-center justify-center mb-6">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
              </div>
              <h3 class="text-xl font-semibold text-gray-900 mb-3">Task Management</h3>
              <p class="text-gray-600 leading-relaxed">
                Organize tasks with drag-and-drop simplicity. Set priorities, deadlines, and track progress effortlessly.
              </p>
            </div>

            <!-- Feature 2 -->
            <div class="bg-white p-8 rounded-xl shadow-sm hover:shadow-md transition-shadow">
              <div class="w-14 h-14 bg-blue-50 rounded-lg flex items-center justify-center mb-6">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
              </div>
              <h3 class="text-xl font-semibold text-gray-900 mb-3">Team Collaboration</h3>
              <p class="text-gray-600 leading-relaxed">
                Real-time updates, comments, and file sharing to keep everyone aligned and productive.
              </p>
            </div>

            <!-- Feature 3 -->
            <div class="bg-white p-8 rounded-xl shadow-sm hover:shadow-md transition-shadow">
              <div class="w-14 h-14 bg-blue-50 rounded-lg flex items-center justify-center mb-6">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
              </div>
              <h3 class="text-xl font-semibold text-gray-900 mb-3">Advanced Analytics</h3>
              <p class="text-gray-600 leading-relaxed">
                Detailed reports and insights to measure performance and make data-driven decisions.
              </p>
            </div>
          </div>
        </div>
      </section>
    </main>

    <!-- Footer -->
    <footer class="border-t border-gray-100 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="text-center text-sm text-gray-600">
          &copy; 2025 Workstation. All rights reserved.
          Author Thabet Hamdi
          <span class="mx-2">•</span>
          <Link href="#" class="hover:text-blue-600 transition-colors">Privacy</Link>
          <span class="mx-2">•</span>
          <Link href="#" class="hover:text-blue-600 transition-colors">Terms</Link>
        </div>
      </div>
    </footer>
  </div>
</template>

<style scoped>
/* Smooth transitions for interactive elements */
a, button, .transition-shadow {
  transition: all 0.2s ease-in-out;
}

/* Improve heading readability */
h1 {
  letter-spacing: -0.025em;
}
</style>
