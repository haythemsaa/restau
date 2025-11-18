<template>
  <AppLayout>
    <div class="space-y-6">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-3xl font-bold text-gray-900">Établissements</h1>
          <p class="mt-2 text-gray-600">Gérez vos restaurants et établissements</p>
        </div>
        <button @click="showCreateModal = true" class="btn btn-primary">
          Ajouter un établissement
        </button>
      </div>

      <!-- Filters -->
      <div class="card">
        <div class="flex items-center space-x-4">
          <div class="flex-1">
            <input
              v-model="filters.search"
              type="text"
              placeholder="Rechercher un établissement..."
              class="input"
            />
          </div>
          <select v-model="filters.status" class="input max-w-xs">
            <option value="">Tous les statuts</option>
            <option value="active">Actif</option>
            <option value="inactive">Inactif</option>
            <option value="suspended">Suspendu</option>
          </select>
        </div>
      </div>

      <!-- Businesses List -->
      <div v-if="loading" class="text-center py-12">
        <p class="text-gray-600">Chargement...</p>
      </div>

      <div v-else-if="businesses.length === 0" class="text-center py-12">
        <p class="text-gray-600">Aucun établissement trouvé</p>
      </div>

      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="business in businesses"
          :key="business.id"
          class="card hover:shadow-lg transition-shadow cursor-pointer"
          @click="$router.push(`/businesses/${business.id}`)"
        >
          <div class="flex items-start justify-between mb-4">
            <div>
              <h3 class="text-lg font-bold text-gray-900">{{ business.name }}</h3>
              <p class="text-sm text-gray-600">{{ business.type }}</p>
            </div>
            <span
              class="px-2 py-1 text-xs font-medium rounded-full"
              :class="{
                'bg-green-100 text-green-800': business.status === 'active',
                'bg-gray-100 text-gray-800': business.status === 'inactive',
                'bg-red-100 text-red-800': business.status === 'suspended',
              }"
            >
              {{ business.status }}
            </span>
          </div>

          <div class="space-y-2 text-sm text-gray-600">
            <p v-if="business.address?.city">
              📍 {{ business.address.city }}, {{ business.address.country }}
            </p>
            <p v-if="business.phone">📞 {{ business.phone }}</p>
            <p v-if="business.email">✉️ {{ business.email }}</p>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { api } from '@/services/api';
import AppLayout from '@/components/Layout/AppLayout.vue';
import type { Business } from '@/types';

const businesses = ref<Business[]>([]);
const loading = ref(false);
const showCreateModal = ref(false);

const filters = ref({
  search: '',
  status: '',
});

async function fetchBusinesses() {
  loading.value = true;
  try {
    const response = await api.getBusinesses({
      status: filters.value.status || undefined,
    });
    businesses.value = response.data;
  } catch (error) {
    console.error('Failed to fetch businesses:', error);
  } finally {
    loading.value = false;
  }
}

onMounted(() => {
  fetchBusinesses();
});
</script>
