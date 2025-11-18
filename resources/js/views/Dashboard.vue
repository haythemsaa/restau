<template>
  <AppLayout>
    <div class="space-y-6">
      <div>
        <h1 class="text-3xl font-bold text-gray-900">Tableau de bord</h1>
        <p class="mt-2 text-gray-600">Bienvenue, {{ authStore.user?.name }}</p>
      </div>

      <!-- Stats Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="card">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Établissements</p>
              <p class="mt-2 text-3xl font-bold text-gray-900">
                {{ stats.businesses }}
              </p>
            </div>
            <div class="h-12 w-12 bg-primary-100 rounded-lg flex items-center justify-center">
              <svg class="h-6 w-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
              </svg>
            </div>
          </div>
        </div>

        <div class="card">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Avis ce mois</p>
              <p class="mt-2 text-3xl font-bold text-gray-900">
                {{ stats.reviews }}
              </p>
            </div>
            <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
              <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
              </svg>
            </div>
          </div>
        </div>

        <div class="card">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Publications actives</p>
              <p class="mt-2 text-3xl font-bold text-gray-900">
                {{ stats.socialPosts }}
              </p>
            </div>
            <div class="h-12 w-12 bg-green-100 rounded-lg flex items-center justify-center">
              <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
              </svg>
            </div>
          </div>
        </div>

        <div class="card">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Conversations ouvertes</p>
              <p class="mt-2 text-3xl font-bold text-gray-900">
                {{ stats.conversations }}
              </p>
            </div>
            <div class="h-12 w-12 bg-purple-100 rounded-lg flex items-center justify-center">
              <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="card">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Actions rapides</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <router-link to="/businesses" class="btn btn-primary">
            Gérer les établissements
          </router-link>
          <router-link to="/reviews" class="btn btn-secondary">
            Voir les avis
          </router-link>
          <router-link to="/social-posts" class="btn btn-secondary">
            Créer une publication
          </router-link>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useAuthStore } from '@/stores/auth';
import AppLayout from '@/components/Layout/AppLayout.vue';

const authStore = useAuthStore();

const stats = ref({
  businesses: 0,
  reviews: 0,
  socialPosts: 0,
  conversations: 0,
});

onMounted(() => {
  // TODO: Fetch real stats from API
  stats.value = {
    businesses: authStore.user?.businesses?.length || 0,
    reviews: 0,
    socialPosts: 0,
    conversations: 0,
  };
});
</script>
