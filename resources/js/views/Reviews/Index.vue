<template>
  <AppLayout>
    <div class="space-y-6">
      <div>
        <h1 class="text-3xl font-bold text-gray-900">Avis clients</h1>
        <p class="mt-2 text-gray-600">Gérez et répondez aux avis de vos établissements</p>
      </div>

      <!-- Filters -->
      <div class="card">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <select v-model="filters.platform" class="input" @change="fetchReviews">
            <option value="">Toutes les plateformes</option>
            <option value="google">Google</option>
            <option value="facebook">Facebook</option>
            <option value="tripadvisor">TripAdvisor</option>
            <option value="yelp">Yelp</option>
          </select>
          <select v-model="filters.rating" class="input" @change="fetchReviews">
            <option value="">Toutes les notes</option>
            <option value="5">5 étoiles</option>
            <option value="4">4 étoiles</option>
            <option value="3">3 étoiles</option>
            <option value="2">2 étoiles</option>
            <option value="1">1 étoile</option>
          </select>
          <select v-model="filters.replied" class="input" @change="fetchReviews">
            <option value="">Tous les avis</option>
            <option value="yes">Avec réponse</option>
            <option value="no">Sans réponse</option>
          </select>
        </div>
      </div>

      <!-- Reviews List -->
      <div v-if="loading" class="text-center py-12">
        <p class="text-gray-600">Chargement...</p>
      </div>

      <div v-else-if="reviews.length === 0" class="text-center py-12">
        <p class="text-gray-600">Aucun avis trouvé</p>
      </div>

      <div v-else class="space-y-4">
        <div v-for="review in reviews" :key="review.id" class="card">
          <div class="flex items-start justify-between mb-4">
            <div class="flex items-center space-x-3">
              <div
                v-if="review.author_photo"
                class="h-12 w-12 rounded-full bg-gray-200"
                :style="{ backgroundImage: `url(${review.author_photo})`, backgroundSize: 'cover' }"
              />
              <div v-else class="h-12 w-12 rounded-full bg-primary-100 flex items-center justify-center">
                <span class="text-primary-700 font-medium">
                  {{ review.author_name.charAt(0).toUpperCase() }}
                </span>
              </div>
              <div>
                <h3 class="font-medium text-gray-900">{{ review.author_name }}</h3>
                <p class="text-sm text-gray-600">
                  {{ new Date(review.published_at).toLocaleDateString('fr-FR') }}
                </p>
              </div>
            </div>
            <div class="flex items-center space-x-2">
              <span class="px-2 py-1 text-xs font-medium rounded bg-gray-100 text-gray-800">
                {{ review.platform }}
              </span>
              <div class="flex">
                <span
                  v-for="i in 5"
                  :key="i"
                  class="text-yellow-400"
                >
                  {{ i <= review.rating ? '★' : '☆' }}
                </span>
              </div>
            </div>
          </div>

          <p class="text-gray-700 mb-4">{{ review.text }}</p>

          <div v-if="review.sentiment_score" class="mb-4">
            <div class="flex items-center space-x-2">
              <span class="text-sm text-gray-600">Sentiment:</span>
              <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden max-w-xs">
                <div
                  class="h-full transition-all"
                  :class="{
                    'bg-green-500': review.sentiment_score > 0.3,
                    'bg-yellow-500': review.sentiment_score >= -0.3 && review.sentiment_score <= 0.3,
                    'bg-red-500': review.sentiment_score < -0.3,
                  }"
                  :style="{ width: `${((review.sentiment_score + 1) / 2) * 100}%` }"
                />
              </div>
            </div>
          </div>

          <div v-if="review.categories && review.categories.length > 0" class="mb-4">
            <div class="flex flex-wrap gap-2">
              <span
                v-for="category in review.categories"
                :key="category"
                class="px-2 py-1 text-xs font-medium rounded bg-blue-100 text-blue-800"
              >
                {{ category }}
              </span>
            </div>
          </div>

          <div v-if="review.reply" class="pl-4 border-l-2 border-primary-200 bg-primary-50 p-4 rounded">
            <p class="text-sm font-medium text-primary-900 mb-1">Votre réponse:</p>
            <p class="text-sm text-primary-800">{{ review.reply }}</p>
            <p class="text-xs text-primary-600 mt-2">
              {{ new Date(review.replied_at!).toLocaleDateString('fr-FR') }}
            </p>
          </div>

          <div v-else class="mt-4">
            <button class="btn btn-primary btn-sm">
              Répondre à cet avis
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
import type { Review } from '@/types';

const reviews = ref<Review[]>([]);
const loading = ref(false);

const filters = ref({
  platform: '',
  rating: '',
  replied: '',
});

async function fetchReviews() {
  loading.value = true;
  try {
    const response = await api.getReviews({
      platform: filters.value.platform || undefined,
      rating: filters.value.rating ? Number(filters.value.rating) : undefined,
    });
    reviews.value = response.data;
  } catch (error) {
    console.error('Failed to fetch reviews:', error);
  } finally {
    loading.value = false;
  }
}

onMounted(() => {
  fetchReviews();
});
</script>
