<template>
  <div class="card">
    <div class="flex justify-between items-center mb-6">
      <h3 class="text-2xl font-bold">👥 Clients</h3>
      <button @click="showCreateModal = true" class="btn btn-primary">
        ➕ Nouveau client
      </button>
    </div>

    <!-- Filters -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Recherche</label>
        <input
          v-model="filters.search"
          type="text"
          class="input"
          placeholder="Nom, email..."
          @input="debouncedSearch"
        />
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Tier</label>
        <select v-model="filters.tier" @change="loadCustomers" class="input">
          <option value="">Tous</option>
          <option value="regular">Regular</option>
          <option value="vip">VIP</option>
          <option value="super_vip">Super VIP</option>
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Segment</label>
        <select v-model="filters.segment_id" @change="loadCustomers" class="input">
          <option value="">Tous les segments</option>
          <option v-for="segment in segments" :key="segment.id" :value="segment.id">
            {{ segment.name }}
          </option>
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Filtres rapides</label>
        <div class="flex space-x-2">
          <button
            @click="toggleFilter('vip_only')"
            :class="['btn btn-sm', filters.vip_only ? 'btn-primary' : 'btn-secondary']"
          >
            👑 VIP
          </button>
          <button
            @click="toggleFilter('at_risk_only')"
            :class="['btn btn-sm', filters.at_risk_only ? 'btn-warning' : 'btn-secondary']"
          >
            ⚠️ À risque
          </button>
        </div>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
      <div class="stat-card">
        <div class="stat-label">Total Clients</div>
        <div class="stat-value">{{ pagination.total || 0 }}</div>
      </div>
      <div class="stat-card">
        <div class="stat-label">VIP</div>
        <div class="stat-value text-yellow-600">{{ stats.vip || 0 }}</div>
      </div>
      <div class="stat-card">
        <div class="stat-label">À risque</div>
        <div class="stat-value text-red-600">{{ stats.at_risk || 0 }}</div>
      </div>
      <div class="stat-card">
        <div class="stat-label">LTV Moyenne</div>
        <div class="stat-value text-green-600">{{ stats.avg_ltv || '0' }}€</div>
      </div>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="text-center py-8">
      <div class="spinner"></div>
      <p class="text-gray-600 mt-2">Chargement...</p>
    </div>

    <!-- Customer Table -->
    <div v-else-if="customers.length > 0" class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Client
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Contact
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Tier
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Visites
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              LTV
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Dernière visite
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Actions
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="customer in customers" :key="customer.id" class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10">
                  <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold">
                    {{ customer.first_name[0] }}{{ customer.last_name[0] }}
                  </div>
                </div>
                <div class="ml-4">
                  <div class="text-sm font-medium text-gray-900">
                    {{ customer.full_name }}
                  </div>
                  <div v-if="customer.tags && customer.tags.length" class="flex gap-1 mt-1">
                    <span
                      v-for="tag in customer.tags.slice(0, 2)"
                      :key="tag"
                      class="px-2 py-0.5 text-xs bg-gray-100 text-gray-600 rounded"
                    >
                      {{ tag }}
                    </span>
                  </div>
                </div>
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm text-gray-900">{{ customer.email }}</div>
              <div v-if="customer.phone" class="text-sm text-gray-500">{{ customer.phone }}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span
                :class="[
                  'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                  getTierBadgeClass(customer.tier)
                ]"
              >
                {{ getTierLabel(customer.tier) }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
              {{ customer.visit_count }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">
              {{ customer.lifetime_value }}€
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ formatDate(customer.last_visit_at) }}
              <span v-if="customer.days_since_last_visit > 60" class="text-red-500 ml-1">⚠️</span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
              <button
                @click="viewCustomer(customer)"
                class="text-blue-600 hover:text-blue-900 mr-3"
              >
                👁️ Voir
              </button>
              <button
                @click="editCustomer(customer)"
                class="text-indigo-600 hover:text-indigo-900"
              >
                ✏️ Modifier
              </button>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination -->
      <div class="mt-6 flex items-center justify-between">
        <div class="text-sm text-gray-700">
          Affichage de {{ pagination.from }} à {{ pagination.to }} sur {{ pagination.total }} clients
        </div>
        <div class="flex space-x-2">
          <button
            @click="loadPage(pagination.current_page - 1)"
            :disabled="!pagination.prev_page_url"
            class="btn btn-sm btn-secondary"
          >
            ← Précédent
          </button>
          <button
            @click="loadPage(pagination.current_page + 1)"
            :disabled="!pagination.next_page_url"
            class="btn btn-sm btn-secondary"
          >
            Suivant →
          </button>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-else class="text-center py-12">
      <div class="text-6xl mb-4">👥</div>
      <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun client trouvé</h3>
      <p class="text-gray-500">Commencez par ajouter votre premier client</p>
    </div>

    <!-- Error -->
    <div v-if="error" class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
      <p class="text-red-600 text-sm">{{ error }}</p>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue';
import axios from 'axios';

const props = defineProps<{
  businessId: string;
}>();

const customers = ref<any[]>([]);
const segments = ref<any[]>([]);
const loading = ref(false);
const error = ref('');
const showCreateModal = ref(false);

const filters = reactive({
  search: '',
  tier: '',
  segment_id: '',
  vip_only: false,
  at_risk_only: false,
});

const stats = reactive({
  vip: 0,
  at_risk: 0,
  avg_ltv: '0.00',
});

const pagination = reactive({
  total: 0,
  from: 0,
  to: 0,
  current_page: 1,
  last_page: 1,
  per_page: 15,
  prev_page_url: null,
  next_page_url: null,
});

let searchTimeout: number;

const debouncedSearch = () => {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    loadCustomers();
  }, 500);
};

const loadCustomers = async (page = 1) => {
  loading.value = true;
  error.value = '';

  try {
    const params = new URLSearchParams({
      page: page.toString(),
      per_page: pagination.per_page.toString(),
    });

    if (filters.search) params.append('search', filters.search);
    if (filters.tier) params.append('tier', filters.tier);
    if (filters.segment_id) params.append('segment_id', filters.segment_id);
    if (filters.vip_only) params.append('vip_only', '1');
    if (filters.at_risk_only) params.append('at_risk_only', '1');

    const response = await axios.get(`/api/v1/customers?${params}`);

    if (response.data.data) {
      customers.value = response.data.data;
      Object.assign(pagination, {
        total: response.data.total,
        from: response.data.from,
        to: response.data.to,
        current_page: response.data.current_page,
        last_page: response.data.last_page,
        prev_page_url: response.data.prev_page_url,
        next_page_url: response.data.next_page_url,
      });
    }
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Erreur lors du chargement des clients';
    console.error('Load customers error:', err);
  } finally {
    loading.value = false;
  }
};

const loadSegments = async () => {
  try {
    const response = await axios.get('/api/v1/customers-segments');
    segments.value = response.data.data || [];
  } catch (err) {
    console.error('Load segments error:', err);
  }
};

const loadStats = async () => {
  try {
    const [vipRes, atRiskRes] = await Promise.all([
      axios.get('/api/v1/customers-vips?per_page=1'),
      axios.get('/api/v1/customers-at-risk?per_page=1'),
    ]);

    stats.vip = vipRes.data.total || 0;
    stats.at_risk = atRiskRes.data.total || 0;

    // Calculate average LTV from current customers
    if (customers.value.length > 0) {
      const totalLtv = customers.value.reduce((sum, c) => sum + parseFloat(c.lifetime_value), 0);
      stats.avg_ltv = (totalLtv / customers.value.length).toFixed(2);
    }
  } catch (err) {
    console.error('Load stats error:', err);
  }
};

const toggleFilter = (filterName: 'vip_only' | 'at_risk_only') => {
  filters[filterName] = !filters[filterName];
  loadCustomers();
};

const loadPage = (page: number) => {
  loadCustomers(page);
};

const viewCustomer = (customer: any) => {
  console.log('View customer:', customer);
  // Navigate to customer detail page
  window.location.href = `/customers/${customer.id}`;
};

const editCustomer = (customer: any) => {
  console.log('Edit customer:', customer);
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
  if (!date) return 'Jamais';
  const d = new Date(date);
  return d.toLocaleDateString('fr-FR', { year: 'numeric', month: 'short', day: 'numeric' });
};

onMounted(() => {
  loadCustomers();
  loadSegments();
  loadStats();
});
</script>

<style scoped>
.input {
  @apply w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent;
}

.btn-sm {
  @apply px-3 py-1.5 text-sm;
}

.btn-warning {
  @apply bg-orange-500 text-white hover:bg-orange-600;
}

.stat-card {
  @apply p-4 bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg border border-gray-200;
}

.stat-label {
  @apply text-sm font-medium text-gray-600 mb-1;
}

.stat-value {
  @apply text-2xl font-bold text-gray-900;
}

.spinner {
  @apply inline-block w-8 h-8 border-4 border-gray-200 border-t-primary-600 rounded-full animate-spin;
}
</style>
