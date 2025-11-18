<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Sidebar -->
    <aside class="fixed inset-y-0 left-0 w-64 bg-white border-r border-gray-200">
      <div class="flex flex-col h-full">
        <!-- Logo -->
        <div class="flex items-center justify-center h-16 border-b border-gray-200">
          <h1 class="text-2xl font-bold text-primary-600">RestauBoost</h1>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-1">
          <router-link
            v-for="item in navigation"
            :key="item.name"
            :to="item.to"
            class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors"
            :class="[
              $route.name === item.name
                ? 'bg-primary-50 text-primary-700'
                : 'text-gray-700 hover:bg-gray-100'
            ]"
          >
            <span>{{ item.label }}</span>
          </router-link>
        </nav>

        <!-- User menu -->
        <div class="p-4 border-t border-gray-200">
          <div class="flex items-center space-x-3">
            <div class="flex-shrink-0">
              <div class="h-10 w-10 rounded-full bg-primary-100 flex items-center justify-center">
                <span class="text-primary-700 font-medium">
                  {{ authStore.user?.name.charAt(0).toUpperCase() }}
                </span>
              </div>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-medium text-gray-900 truncate">
                {{ authStore.user?.name }}
              </p>
              <p class="text-xs text-gray-500 truncate">
                {{ authStore.user?.email }}
              </p>
            </div>
          </div>
          <button
            @click="handleLogout"
            class="mt-4 w-full btn btn-secondary text-sm"
          >
            Déconnexion
          </button>
        </div>
      </div>
    </aside>

    <!-- Main content -->
    <main class="pl-64">
      <div class="py-6 px-8">
        <slot />
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

const router = useRouter();
const authStore = useAuthStore();

const navigation = [
  { name: 'dashboard', label: 'Tableau de bord', to: '/dashboard' },
  { name: 'businesses', label: 'Établissements', to: '/businesses' },
  { name: 'reviews', label: 'Avis clients', to: '/reviews' },
  { name: 'social-posts', label: 'Publications', to: '/social-posts' },
  { name: 'conversations', label: 'Messagerie', to: '/conversations' },
];

async function handleLogout() {
  await authStore.logout();
  router.push('/login');
}
</script>
