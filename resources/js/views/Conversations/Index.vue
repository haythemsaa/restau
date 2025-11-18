<template>
  <AppLayout>
    <div class="space-y-6">
      <div>
        <h1 class="text-3xl font-bold text-gray-900">Messagerie unifiée</h1>
        <p class="mt-2 text-gray-600">Toutes vos conversations clients en un seul endroit</p>
      </div>

      <!-- Filters -->
      <div class="card">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <select v-model="filters.platform" class="input" @change="fetchConversations">
            <option value="">Toutes les plateformes</option>
            <option value="google">Google</option>
            <option value="facebook">Facebook</option>
            <option value="instagram">Instagram</option>
            <option value="whatsapp">WhatsApp</option>
            <option value="messenger">Messenger</option>
          </select>
          <select v-model="filters.status" class="input" @change="fetchConversations">
            <option value="">Tous les statuts</option>
            <option value="open">Ouvert</option>
            <option value="pending">En attente</option>
            <option value="resolved">Résolu</option>
            <option value="closed">Fermé</option>
          </select>
        </div>
      </div>

      <!-- Conversations List -->
      <div v-if="loading" class="text-center py-12">
        <p class="text-gray-600">Chargement...</p>
      </div>

      <div v-else-if="conversations.length === 0" class="text-center py-12">
        <p class="text-gray-600">Aucune conversation trouvée</p>
      </div>

      <div v-else class="space-y-4">
        <div
          v-for="conversation in conversations"
          :key="conversation.id"
          class="card hover:shadow-lg transition-shadow cursor-pointer"
        >
          <div class="flex items-start justify-between">
            <div class="flex items-start space-x-4 flex-1">
              <div class="h-12 w-12 rounded-full bg-primary-100 flex items-center justify-center flex-shrink-0">
                <span class="text-primary-700 font-medium">
                  {{ (conversation.customer_name || 'A').charAt(0).toUpperCase() }}
                </span>
              </div>
              <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between mb-1">
                  <h3 class="font-medium text-gray-900">
                    {{ conversation.customer_name || 'Client anonyme' }}
                  </h3>
                  <span class="text-xs text-gray-500">
                    {{ formatDate(conversation.last_message_at || conversation.created_at) }}
                  </span>
                </div>
                <div class="flex items-center space-x-2 mb-2">
                  <span class="px-2 py-1 text-xs font-medium rounded bg-gray-100 text-gray-800">
                    {{ conversation.platform }}
                  </span>
                  <span
                    class="px-2 py-1 text-xs font-medium rounded-full"
                    :class="{
                      'bg-green-100 text-green-800': conversation.status === 'open',
                      'bg-yellow-100 text-yellow-800': conversation.status === 'pending',
                      'bg-blue-100 text-blue-800': conversation.status === 'resolved',
                      'bg-gray-100 text-gray-800': conversation.status === 'closed',
                    }"
                  >
                    {{ statusLabels[conversation.status] }}
                  </span>
                </div>
                <p v-if="conversation.customer_email" class="text-sm text-gray-600">
                  {{ conversation.customer_email }}
                </p>
                <p v-if="conversation.customer_phone" class="text-sm text-gray-600">
                  {{ conversation.customer_phone }}
                </p>
              </div>
            </div>
            <button class="btn btn-primary btn-sm ml-4">
              Voir
            </button>
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
import type { Conversation } from '@/types';

const conversations = ref<Conversation[]>([]);
const loading = ref(false);

const filters = ref({
  platform: '',
  status: '',
});

const statusLabels: Record<string, string> = {
  open: 'Ouvert',
  pending: 'En attente',
  resolved: 'Résolu',
  closed: 'Fermé',
};

function formatDate(dateString: string) {
  const date = new Date(dateString);
  const now = new Date();
  const diff = now.getTime() - date.getTime();
  const minutes = Math.floor(diff / 60000);
  const hours = Math.floor(diff / 3600000);
  const days = Math.floor(diff / 86400000);

  if (minutes < 60) return `Il y a ${minutes} min`;
  if (hours < 24) return `Il y a ${hours}h`;
  if (days < 7) return `Il y a ${days}j`;
  return date.toLocaleDateString('fr-FR');
}

async function fetchConversations() {
  loading.value = true;
  try {
    const response = await api.getConversations({
      platform: filters.value.platform || undefined,
      status: filters.value.status || undefined,
    });
    conversations.value = response.data;
  } catch (error) {
    console.error('Failed to fetch conversations:', error);
  } finally {
    loading.value = false;
  }
}

onMounted(() => {
  fetchConversations();
});
</script>
