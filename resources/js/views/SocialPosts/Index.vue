<template>
  <AppLayout>
    <div class="space-y-6">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-3xl font-bold text-gray-900">Publications sociales</h1>
          <p class="mt-2 text-gray-600">Gérez vos publications sur les réseaux sociaux</p>
        </div>
        <button class="btn btn-primary">
          Nouvelle publication
        </button>
      </div>

      <!-- Filters -->
      <div class="card">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <select v-model="filters.status" class="input" @change="fetchPosts">
            <option value="">Tous les statuts</option>
            <option value="draft">Brouillon</option>
            <option value="scheduled">Programmé</option>
            <option value="published">Publié</option>
            <option value="failed">Échoué</option>
          </select>
        </div>
      </div>

      <!-- Posts List -->
      <div v-if="loading" class="text-center py-12">
        <p class="text-gray-600">Chargement...</p>
      </div>

      <div v-else-if="posts.length === 0" class="text-center py-12">
        <p class="text-gray-600">Aucune publication trouvée</p>
      </div>

      <div v-else class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div v-for="post in posts" :key="post.id" class="card">
          <div class="flex items-start justify-between mb-4">
            <span
              class="px-2 py-1 text-xs font-medium rounded-full"
              :class="{
                'bg-gray-100 text-gray-800': post.status === 'draft',
                'bg-blue-100 text-blue-800': post.status === 'scheduled',
                'bg-green-100 text-green-800': post.status === 'published',
                'bg-red-100 text-red-800': post.status === 'failed',
              }"
            >
              {{ statusLabels[post.status] }}
            </span>
            <div class="flex space-x-2">
              <span
                v-for="platform in post.platforms"
                :key="platform"
                class="px-2 py-1 text-xs font-medium rounded bg-primary-100 text-primary-800"
              >
                {{ platform }}
              </span>
            </div>
          </div>

          <p class="text-gray-700 mb-4 line-clamp-3">{{ post.content }}</p>

          <div v-if="post.media_urls && post.media_urls.length > 0" class="mb-4">
            <div class="flex space-x-2">
              <div
                v-for="(url, idx) in post.media_urls.slice(0, 3)"
                :key="idx"
                class="h-20 w-20 bg-gray-200 rounded"
              >
                <img :src="url" :alt="`Media ${idx + 1}`" class="h-full w-full object-cover rounded" />
              </div>
              <div
                v-if="post.media_urls.length > 3"
                class="h-20 w-20 bg-gray-100 rounded flex items-center justify-center text-gray-600"
              >
                +{{ post.media_urls.length - 3 }}
              </div>
            </div>
          </div>

          <div class="flex items-center justify-between text-sm text-gray-600">
            <div>
              <p v-if="post.scheduled_for">
                📅 {{ new Date(post.scheduled_for).toLocaleString('fr-FR') }}
              </p>
              <p v-else-if="post.published_at">
                ✅ {{ new Date(post.published_at).toLocaleString('fr-FR') }}
              </p>
              <p v-else>
                📝 Créé le {{ new Date(post.created_at).toLocaleDateString('fr-FR') }}
              </p>
            </div>
            <div class="flex space-x-2">
              <button v-if="post.status === 'draft'" class="btn btn-primary btn-sm">
                Programmer
              </button>
              <button v-if="post.status === 'scheduled'" class="btn btn-secondary btn-sm">
                Modifier
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
import { api } from '@/services/api';
import AppLayout from '@/components/Layout/AppLayout.vue';
import type { SocialPost } from '@/types';

const posts = ref<SocialPost[]>([]);
const loading = ref(false);

const filters = ref({
  status: '',
});

const statusLabels: Record<string, string> = {
  draft: 'Brouillon',
  scheduled: 'Programmé',
  publishing: 'Publication...',
  published: 'Publié',
  failed: 'Échoué',
};

async function fetchPosts() {
  loading.value = true;
  try {
    const response = await api.getSocialPosts({
      status: filters.value.status || undefined,
    });
    posts.value = response.data;
  } catch (error) {
    console.error('Failed to fetch posts:', error);
  } finally {
    loading.value = false;
  }
}

onMounted(() => {
  fetchPosts();
});
</script>
