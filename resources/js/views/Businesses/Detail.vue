<template>
  <AppLayout>
    <div v-if="loading" class="text-center py-12">
      <p class="text-gray-600">Chargement...</p>
    </div>

    <div v-else-if="business" class="space-y-6">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-3xl font-bold text-gray-900">{{ business.name }}</h1>
          <p class="mt-2 text-gray-600">{{ business.type }}</p>
        </div>
        <button class="btn btn-primary">
          Modifier
        </button>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
          <div class="card">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Informations</h2>
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <dt class="text-sm font-medium text-gray-600">Statut</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ business.status }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-600">Fuseau horaire</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ business.timezone }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-600">Téléphone</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ business.phone || 'N/A' }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-600">Email</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ business.email || 'N/A' }}</dd>
              </div>
              <div class="md:col-span-2">
                <dt class="text-sm font-medium text-gray-600">Site web</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ business.website || 'N/A' }}</dd>
              </div>
            </dl>
          </div>

          <div class="card">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Adresse</h2>
            <address class="not-italic text-gray-700">
              <p v-if="business.address?.street">{{ business.address.street }}</p>
              <p>
                <span v-if="business.address?.postal_code">{{ business.address.postal_code }}</span>
                <span v-if="business.address?.city"> {{ business.address.city }}</span>
              </p>
              <p v-if="business.address?.country">{{ business.address.country }}</p>
            </address>
          </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
          <div class="card">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Actions rapides</h2>
            <div class="space-y-2">
              <button class="w-full btn btn-secondary text-left">
                Voir les avis
              </button>
              <button class="w-full btn btn-secondary text-left">
                Créer une publication
              </button>
              <button class="w-full btn btn-secondary text-left">
                Messagerie
              </button>
              <button class="w-full btn btn-secondary text-left">
                Statistiques
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { api } from '@/services/api';
import AppLayout from '@/components/Layout/AppLayout.vue';
import type { Business } from '@/types';

const route = useRoute();
const business = ref<Business | null>(null);
const loading = ref(false);

async function fetchBusiness() {
  loading.value = true;
  try {
    const response = await api.getBusiness(route.params.id as string);
    business.value = response.data;
  } catch (error) {
    console.error('Failed to fetch business:', error);
  } finally {
    loading.value = false;
  }
}

onMounted(() => {
  fetchBusiness();
});
</script>
