<template>
  <div class="max-w-7xl mx-auto">
    <!-- Loading -->
    <div v-if="loading" class="text-center py-12">
      <div class="spinner"></div>
      <p class="text-gray-600 mt-2">Chargement...</p>
    </div>

    <!-- Customer Details -->
    <div v-else-if="customer" class="space-y-6">
      <!-- Header -->
      <div class="card">
        <div class="flex items-start justify-between">
          <div class="flex items-center space-x-4">
            <div class="h-20 w-20 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-3xl font-bold">
              {{ customer.first_name[0] }}{{ customer.last_name[0] }}
            </div>
            <div>
              <h1 class="text-3xl font-bold text-gray-900">{{ customer.full_name }}</h1>
              <div class="flex items-center space-x-3 mt-2">
                <span
                  :class="[
                    'px-3 py-1 text-sm font-semibold rounded-full',
                    getTierBadgeClass(customer.tier)
                  ]"
                >
                  {{ getTierLabel(customer.tier) }}
                </span>
                <span v-if="stats?.at_risk" class="px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                  ⚠️ À risque
                </span>
                <span v-if="stats?.is_birthday" class="px-3 py-1 text-sm font-semibold rounded-full bg-pink-100 text-pink-800">
                  🎂 Anniversaire ce mois-ci
                </span>
              </div>
            </div>
          </div>
          <button @click="editCustomer" class="btn btn-secondary">
            ✏️ Modifier
          </button>
        </div>

        <!-- Contact Info -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="text-sm font-medium text-gray-500">Email</label>
            <p class="text-gray-900">{{ customer.email }}</p>
          </div>
          <div v-if="customer.phone">
            <label class="text-sm font-medium text-gray-500">Téléphone</label>
            <p class="text-gray-900">{{ customer.phone }}</p>
          </div>
          <div v-if="customer.birth_date">
            <label class="text-sm font-medium text-gray-500">Date de naissance</label>
            <p class="text-gray-900">{{ formatDate(customer.birth_date) }} ({{ customer.age }} ans)</p>
          </div>
        </div>

        <!-- Tags -->
        <div v-if="customer.tags && customer.tags.length" class="mt-4">
          <label class="text-sm font-medium text-gray-500 block mb-2">Tags</label>
          <div class="flex flex-wrap gap-2">
            <span
              v-for="tag in customer.tags"
              :key="tag"
              class="px-3 py-1 text-sm bg-blue-100 text-blue-800 rounded-full"
            >
              {{ tag }}
            </span>
          </div>
        </div>
      </div>

      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="stat-card">
          <div class="stat-label">💰 Lifetime Value</div>
          <div class="stat-value text-green-600">{{ customer.lifetime_value }}€</div>
        </div>
        <div class="stat-card">
          <div class="stat-label">🔢 Nombre de visites</div>
          <div class="stat-value">{{ customer.visit_count }}</div>
        </div>
        <div class="stat-card">
          <div class="stat-label">📊 Dépense moyenne</div>
          <div class="stat-value text-blue-600">{{ customer.average_spend }}€</div>
        </div>
        <div class="stat-card">
          <div class="stat-label">📅 Dernière visite</div>
          <div class="stat-value text-sm">
            {{ formatDate(customer.last_visit_at) }}
            <div class="text-xs text-gray-500 mt-1">
              Il y a {{ customer.days_since_last_visit }} jours
            </div>
          </div>
        </div>
      </div>

      <!-- RFM Score -->
      <div class="card" v-if="stats?.rfm_score">
        <h3 class="text-xl font-bold mb-4">📈 Score RFM (Recency, Frequency, Monetary)</h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div class="rfm-metric">
            <div class="flex justify-between items-center mb-2">
              <span class="text-sm font-medium text-gray-600">Récence</span>
              <span class="text-2xl font-bold text-blue-600">{{ stats.rfm_score.recency }}/5</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
              <div
                class="bg-blue-600 h-2 rounded-full"
                :style="{ width: `${(stats.rfm_score.recency / 5) * 100}%` }"
              ></div>
            </div>
            <p class="text-xs text-gray-500 mt-1">Temps depuis la dernière visite</p>
          </div>

          <div class="rfm-metric">
            <div class="flex justify-between items-center mb-2">
              <span class="text-sm font-medium text-gray-600">Fréquence</span>
              <span class="text-2xl font-bold text-green-600">{{ stats.rfm_score.frequency }}/5</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
              <div
                class="bg-green-600 h-2 rounded-full"
                :style="{ width: `${(stats.rfm_score.frequency / 5) * 100}%` }"
              ></div>
            </div>
            <p class="text-xs text-gray-500 mt-1">Nombre de visites</p>
          </div>

          <div class="rfm-metric">
            <div class="flex justify-between items-center mb-2">
              <span class="text-sm font-medium text-gray-600">Monétaire</span>
              <span class="text-2xl font-bold text-purple-600">{{ stats.rfm_score.monetary }}/5</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
              <div
                class="bg-purple-600 h-2 rounded-full"
                :style="{ width: `${(stats.rfm_score.monetary / 5) * 100}%` }"
              ></div>
            </div>
            <p class="text-xs text-gray-500 mt-1">Valeur totale dépensée</p>
          </div>
        </div>

        <div class="mt-6 p-4 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-lg border border-indigo-200">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Score Total RFM</p>
              <p class="text-3xl font-bold text-indigo-600">{{ stats.rfm_score.total_score.toFixed(1) }}/5</p>
            </div>
            <div class="text-right">
              <p class="text-sm font-medium text-gray-600">Segment</p>
              <p class="text-xl font-bold text-purple-600">{{ stats.rfm_score.segment }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Preferences -->
      <div class="card" v-if="customer.preferences">
        <h3 class="text-xl font-bold mb-4">🍽️ Préférences</h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div v-if="customer.preferences.dietary && customer.preferences.dietary.length">
            <label class="text-sm font-medium text-gray-600 block mb-2">Régime alimentaire</label>
            <div class="flex flex-wrap gap-2">
              <span
                v-for="diet in customer.preferences.dietary"
                :key="diet"
                class="px-3 py-1 text-sm bg-green-100 text-green-800 rounded-full"
              >
                {{ diet }}
              </span>
            </div>
          </div>

          <div v-if="customer.preferences.allergies && customer.preferences.allergies.length">
            <label class="text-sm font-medium text-gray-600 block mb-2">Allergies</label>
            <div class="flex flex-wrap gap-2">
              <span
                v-for="allergy in customer.preferences.allergies"
                :key="allergy"
                class="px-3 py-1 text-sm bg-red-100 text-red-800 rounded-full"
              >
                ⚠️ {{ allergy }}
              </span>
            </div>
          </div>

          <div v-if="customer.preferences.favorite_dishes && customer.preferences.favorite_dishes.length">
            <label class="text-sm font-medium text-gray-600 block mb-2">Plats favoris</label>
            <div class="flex flex-wrap gap-2">
              <span
                v-for="dish in customer.preferences.favorite_dishes"
                :key="dish"
                class="px-3 py-1 text-sm bg-yellow-100 text-yellow-800 rounded-full"
              >
                ⭐ {{ dish }}
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Segments -->
      <div class="card" v-if="customer.segments && customer.segments.length">
        <h3 class="text-xl font-bold mb-4">🎯 Segments</h3>
        <div class="flex flex-wrap gap-3">
          <span
            v-for="segment in customer.segments"
            :key="segment.id"
            class="px-4 py-2 rounded-lg font-medium"
            :style="{ backgroundColor: segment.color + '20', color: segment.color }"
          >
            {{ segment.name }}
          </span>
        </div>
      </div>

      <!-- Visit History -->
      <div class="card" v-if="customer.latest_visits && customer.latest_visits.length">
        <h3 class="text-xl font-bold mb-4">📜 Historique des visites</h3>

        <div class="space-y-3">
          <div
            v-for="visit in customer.latest_visits"
            :key="visit.id"
            class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50"
          >
            <div class="flex justify-between items-start">
              <div>
                <p class="font-medium text-gray-900">
                  {{ formatDate(visit.visited_at) }} - {{ visit.visited_time }}
                </p>
                <p class="text-sm text-gray-500 mt-1">
                  {{ visit.party_size }} personne(s) • {{ visit.source }}
                </p>
                <div v-if="visit.items_ordered && visit.items_ordered.length" class="mt-2">
                  <p class="text-xs text-gray-500 mb-1">Commandes:</p>
                  <div class="flex flex-wrap gap-1">
                    <span
                      v-for="(item, index) in visit.items_ordered"
                      :key="index"
                      class="px-2 py-0.5 text-xs bg-gray-100 text-gray-600 rounded"
                    >
                      {{ item }}
                    </span>
                  </div>
                </div>
              </div>
              <div class="text-right">
                <p class="text-lg font-bold text-green-600">{{ visit.amount_spent }}€</p>
                <div v-if="visit.satisfaction_score" class="mt-1">
                  <span class="text-yellow-500">
                    {{ '⭐'.repeat(visit.satisfaction_score) }}
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div v-if="customer.visits_count > 5" class="mt-4 text-center">
          <button @click="loadAllVisits" class="btn btn-secondary">
            Voir toutes les visites ({{ customer.visits_count }})
          </button>
        </div>
      </div>

      <!-- Notes -->
      <div class="card" v-if="customer.notes">
        <h3 class="text-xl font-bold mb-4">📝 Notes</h3>
        <p class="text-gray-700 whitespace-pre-wrap">{{ customer.notes }}</p>
      </div>
    </div>

    <!-- Error -->
    <div v-if="error" class="card">
      <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
        <p class="text-red-600 text-sm">{{ error }}</p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import axios from 'axios';

const props = defineProps<{
  customerId: string;
}>();

const customer = ref<any>(null);
const stats = ref<any>(null);
const loading = ref(false);
const error = ref('');

const loadCustomer = async () => {
  loading.value = true;
  error.value = '';

  try {
    const response = await axios.get(`/api/v1/customers/${props.customerId}?include_rfm=1`);

    if (response.data.data) {
      customer.value = response.data.data;
      stats.value = response.data.stats;
    }
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Erreur lors du chargement du client';
    console.error('Load customer error:', err);
  } finally {
    loading.value = false;
  }
};

const loadAllVisits = () => {
  console.log('Load all visits');
  // Navigate to visits page or open modal
};

const editCustomer = () => {
  console.log('Edit customer');
  // Open edit modal or navigate to edit page
};

const getTierBadgeClass = (tier: string) => {
  switch (tier) {
    case 'super_vip':
      return 'bg-purple-100 text-purple-800';
    case 'vip':
      return 'bg-yellow-100 text-yellow-800';
    default:
      return 'bg-gray-100 text-gray-800';
  }
};

const getTierLabel = (tier: string) => {
  switch (tier) {
    case 'super_vip':
      return '⭐ Super VIP';
    case 'vip':
      return '👑 VIP';
    default:
      return 'Regular';
  }
};

const formatDate = (date: string | null) => {
  if (!date) return 'N/A';
  const d = new Date(date);
  return d.toLocaleDateString('fr-FR', { year: 'numeric', month: 'long', day: 'numeric' });
};

onMounted(() => {
  loadCustomer();
});
</script>

<style scoped>
.spinner {
  @apply inline-block w-8 h-8 border-4 border-gray-200 border-t-primary-600 rounded-full animate-spin;
}

.stat-card {
  @apply p-6 bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg border border-gray-200;
}

.stat-label {
  @apply text-sm font-medium text-gray-600 mb-2;
}

.stat-value {
  @apply text-2xl font-bold text-gray-900;
}

.rfm-metric {
  @apply p-4 bg-white rounded-lg border border-gray-200;
}
</style>
