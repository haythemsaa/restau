<template>
  <div class="card">
    <div class="flex justify-between items-center mb-6">
      <h3 class="text-2xl font-bold">🎯 Segments de clients</h3>
      <button @click="createSegment" class="btn btn-primary">
        ➕ Nouveau segment
      </button>
    </div>

    <p class="text-gray-600 mb-6">
      Segmentez vos clients pour mieux cibler vos campagnes marketing et personnaliser vos communications.
    </p>

    <!-- Loading -->
    <div v-if="loading" class="text-center py-8">
      <div class="spinner"></div>
      <p class="text-gray-600 mt-2">Chargement...</p>
    </div>

    <!-- Segments Grid -->
    <div v-else-if="segments.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div
        v-for="segment in segments"
        :key="segment.id"
        class="segment-card"
        :style="{ borderColor: segment.color }"
      >
        <!-- Header -->
        <div class="flex items-start justify-between mb-4">
          <div class="flex-1">
            <div class="flex items-center space-x-2 mb-2">
              <div
                class="w-4 h-4 rounded-full"
                :style="{ backgroundColor: segment.color }"
              ></div>
              <h4 class="text-lg font-bold text-gray-900">{{ segment.name }}</h4>
            </div>
            <p class="text-sm text-gray-600">{{ segment.description }}</p>
          </div>
          <div class="flex space-x-1">
            <button
              @click="editSegment(segment)"
              class="p-2 text-gray-400 hover:text-gray-600"
              title="Modifier"
            >
              ✏️
            </button>
            <button
              v-if="!segment.is_predefined"
              @click="deleteSegment(segment)"
              class="p-2 text-gray-400 hover:text-red-600"
              title="Supprimer"
            >
              🗑️
            </button>
          </div>
        </div>

        <!-- Stats -->
        <div class="mb-4">
          <div class="flex items-center justify-between">
            <span class="text-3xl font-bold" :style="{ color: segment.color }">
              {{ segment.customers_count || 0 }}
            </span>
            <span class="text-sm text-gray-500">clients</span>
          </div>
        </div>

        <!-- Criteria -->
        <div v-if="segment.criteria" class="mb-4 p-3 bg-gray-50 rounded-lg">
          <p class="text-xs font-medium text-gray-500 mb-2">Critères:</p>
          <div class="space-y-1">
            <div v-if="segment.criteria.min_visits" class="text-xs text-gray-700">
              • Visites minimum: {{ segment.criteria.min_visits }}
            </div>
            <div v-if="segment.criteria.min_ltv" class="text-xs text-gray-700">
              • LTV minimum: {{ segment.criteria.min_ltv }}€
            </div>
            <div v-if="segment.criteria.tier" class="text-xs text-gray-700">
              • Tier: {{ segment.criteria.tier }}
            </div>
            <div v-if="segment.criteria.days_since_last_visit" class="text-xs text-gray-700">
              • Jours depuis dernière visite: {{ segment.criteria.days_since_last_visit }}+
            </div>
            <div v-if="segment.criteria.tags && segment.criteria.tags.length" class="text-xs text-gray-700">
              • Tags: {{ segment.criteria.tags.join(', ') }}
            </div>
          </div>
        </div>

        <!-- Auto-update Badge -->
        <div class="flex items-center justify-between">
          <span
            v-if="segment.auto_update"
            class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full"
          >
            ✓ Mise à jour auto
          </span>
          <span
            v-else
            class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-600 rounded-full"
          >
            Manuel
          </span>
          <span
            v-if="segment.is_predefined"
            class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full"
          >
            📌 Prédéfini
          </span>
        </div>

        <!-- Actions -->
        <div class="mt-4 pt-4 border-t border-gray-200 flex space-x-2">
          <button
            @click="viewSegmentCustomers(segment)"
            class="flex-1 btn btn-sm btn-secondary"
          >
            👥 Voir les clients
          </button>
          <button
            v-if="segment.auto_update"
            @click="refreshSegment(segment)"
            :disabled="refreshing[segment.id]"
            class="btn btn-sm btn-secondary"
          >
            {{ refreshing[segment.id] ? '⏳' : '🔄' }}
          </button>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-else class="text-center py-12">
      <div class="text-6xl mb-4">🎯</div>
      <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun segment trouvé</h3>
      <p class="text-gray-500 mb-4">Créez votre premier segment pour organiser vos clients</p>
      <button @click="createPredefinedSegments" class="btn btn-primary">
        ✨ Créer les segments prédéfinis
      </button>
    </div>

    <!-- Error -->
    <div v-if="error" class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
      <p class="text-red-600 text-sm">{{ error }}</p>
    </div>

    <!-- Quick Actions -->
    <div v-if="segments.length > 0" class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
      <h4 class="font-medium text-blue-900 mb-2">💡 Actions rapides</h4>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
        <button @click="viewVipCustomers" class="btn btn-sm btn-secondary text-left">
          <span class="mr-2">👑</span> Voir les clients VIP
        </button>
        <button @click="viewAtRiskCustomers" class="btn btn-sm btn-secondary text-left">
          <span class="mr-2">⚠️</span> Voir les clients à risque
        </button>
        <button @click="viewBirthdayCustomers" class="btn btn-sm btn-secondary text-left">
          <span class="mr-2">🎂</span> Anniversaires du mois
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue';
import axios from 'axios';

const props = defineProps<{
  businessId: string;
}>();

const segments = ref<any[]>([]);
const loading = ref(false);
const error = ref('');
const refreshing = reactive<Record<string, boolean>>({});

const loadSegments = async () => {
  loading.value = true;
  error.value = '';

  try {
    const response = await axios.get('/api/v1/customers-segments');

    if (response.data.data) {
      segments.value = response.data.data;
    }
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Erreur lors du chargement des segments';
    console.error('Load segments error:', err);
  } finally {
    loading.value = false;
  }
};

const createSegment = () => {
  console.log('Create new segment');
  // Open create modal
  alert('Fonctionnalité à implémenter: Créer un nouveau segment');
};

const createPredefinedSegments = async () => {
  try {
    loading.value = true;
    error.value = '';

    // This would call an API endpoint to create predefined segments
    // For now, just reload segments
    await loadSegments();

    alert('Segments prédéfinis créés avec succès!');
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Erreur lors de la création des segments';
  } finally {
    loading.value = false;
  }
};

const editSegment = (segment: any) => {
  console.log('Edit segment:', segment);
  // Open edit modal
  alert(`Fonctionnalité à implémenter: Modifier le segment "${segment.name}"`);
};

const deleteSegment = async (segment: any) => {
  if (!confirm(`Êtes-vous sûr de vouloir supprimer le segment "${segment.name}"?`)) {
    return;
  }

  try {
    await axios.delete(`/api/v1/segments/${segment.id}`);
    segments.value = segments.value.filter(s => s.id !== segment.id);
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Erreur lors de la suppression du segment';
  }
};

const refreshSegment = async (segment: any) => {
  refreshing[segment.id] = true;
  error.value = '';

  try {
    // Call API to refresh segment
    await axios.post(`/api/v1/segments/${segment.id}/refresh`);

    // Reload segments to get updated counts
    await loadSegments();

    alert(`Segment "${segment.name}" mis à jour avec succès!`);
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Erreur lors de la mise à jour du segment';
  } finally {
    refreshing[segment.id] = false;
  }
};

const viewSegmentCustomers = (segment: any) => {
  console.log('View segment customers:', segment);
  // Navigate to customers list filtered by segment
  window.location.href = `/customers?segment_id=${segment.id}`;
};

const viewVipCustomers = () => {
  window.location.href = '/customers?vip_only=1';
};

const viewAtRiskCustomers = () => {
  window.location.href = '/customers?at_risk_only=1';
};

const viewBirthdayCustomers = () => {
  window.location.href = '/customers/birthdays';
};

onMounted(() => {
  loadSegments();
});
</script>

<style scoped>
.spinner {
  @apply inline-block w-8 h-8 border-4 border-gray-200 border-t-primary-600 rounded-full animate-spin;
}

.segment-card {
  @apply p-6 bg-white rounded-lg border-2 hover:shadow-lg transition-shadow;
}

.btn-sm {
  @apply px-3 py-1.5 text-sm;
}
</style>
