<template>
  <div class="card">
    <h3 class="text-2xl font-bold mb-6">🤖 Générateur de Contenu IA</h3>

    <form @submit.prevent="generateContent" class="space-y-6">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Plateforme *
          </label>
          <select v-model="form.platform" required class="input">
            <option value="">Sélectionner...</option>
            <option value="facebook">Facebook</option>
            <option value="instagram">Instagram</option>
            <option value="twitter">Twitter</option>
            <option value="linkedin">LinkedIn</option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Ton du message *
          </label>
          <select v-model="form.tone" required class="input">
            <option value="professional">Professionnel</option>
            <option value="friendly">Amical</option>
            <option value="casual">Décontracté</option>
            <option value="enthusiastic">Enthousiaste</option>
          </select>
        </div>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
          Thème / Sujet *
        </label>
        <input
          v-model="form.theme"
          type="text"
          required
          class="input"
          placeholder="Ex: Nouveau menu d'automne, Événement spécial..."
        />
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
          Public cible
        </label>
        <input
          v-model="form.audience"
          type="text"
          class="input"
          placeholder="Ex: Familles, Couples, Professionnels..."
        />
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
          Détails supplémentaires
        </label>
        <textarea
          v-model="form.details"
          class="input"
          rows="3"
          placeholder="Ajoutez des informations spécifiques (plats, prix, dates...)"
        ></textarea>
      </div>

      <div class="flex space-x-3">
        <button
          type="submit"
          :disabled="loading"
          class="btn btn-primary"
        >
          {{ loading ? '⏳ Génération...' : '✨ Générer avec l\'IA' }}
        </button>

        <button
          v-if="generatedContent"
          type="button"
          @click="generateMultiple"
          :disabled="loading"
          class="btn btn-secondary"
        >
          🔄 Générer plus de variations
        </button>
      </div>
    </form>

    <!-- Results -->
    <div v-if="generatedContent && !loading" class="mt-8 space-y-4">
      <div class="border-t pt-6">
        <h4 class="text-lg font-bold mb-4">Contenu généré :</h4>

        <div class="p-6 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg border border-blue-200">
          <p class="whitespace-pre-wrap text-gray-800 leading-relaxed">
            {{ generatedContent }}
          </p>

          <div v-if="hashtags.length" class="mt-4 pt-4 border-t border-blue-200">
            <p class="text-sm font-medium text-gray-600 mb-2">Hashtags suggérés :</p>
            <div class="flex flex-wrap gap-2">
              <span
                v-for="(tag, index) in hashtags"
                :key="index"
                class="px-3 py-1 text-sm bg-blue-100 text-blue-800 rounded-full"
              >
                {{ tag.startsWith('#') ? tag : '#' + tag }}
              </span>
            </div>
          </div>

          <div v-if="bestTime" class="mt-4 pt-4 border-t border-blue-200">
            <p class="text-sm font-medium text-gray-600 mb-2">📅 Meilleur moment pour publier :</p>
            <div class="grid grid-cols-2 gap-3 text-sm">
              <div>
                <span class="text-gray-600">Jours :</span>
                <span class="ml-2 text-gray-900 font-medium">
                  {{ bestTime.best_days?.join(', ') }}
                </span>
              </div>
              <div>
                <span class="text-gray-600">Heures :</span>
                <span class="ml-2 text-gray-900 font-medium">
                  {{ bestTime.best_hours?.join(', ') }}
                </span>
              </div>
            </div>
          </div>
        </div>

        <div class="mt-4 flex space-x-3">
          <button @click="useContent" class="btn btn-primary">
            ✅ Utiliser ce contenu
          </button>
          <button @click="copyToClipboard" class="btn btn-secondary">
            📋 Copier
          </button>
          <button @click="regenerate" class="btn btn-secondary">
            🔄 Régénérer
          </button>
        </div>
      </div>
    </div>

    <!-- Error -->
    <div v-if="error" class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
      <p class="text-red-600 text-sm">{{ error }}</p>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, computed } from 'vue';
import axios from 'axios';

const props = defineProps<{
  businessId: string;
}>();

const form = reactive({
  business_id: props.businessId,
  platform: 'instagram',
  theme: '',
  tone: 'friendly',
  audience: 'food lovers',
  details: '',
});

const loading = ref(false);
const error = ref('');
const generatedContent = ref('');
const hashtags = ref<string[]>([]);
const bestTime = ref<any>(null);

const generateContent = async () => {
  loading.value = true;
  error.value = '';

  try {
    const response = await axios.post('/api/v1/ai/generate-content', form);

    if (response.data.success) {
      generatedContent.value = response.data.data.content;
      hashtags.value = response.data.data.hashtags || [];
      bestTime.value = response.data.data.best_time;
    }
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Erreur lors de la génération du contenu';
    console.error('Generation error:', err);
  } finally {
    loading.value = false;
  }
};

const generateMultiple = async () => {
  loading.value = true;
  error.value = '';

  try {
    const response = await axios.post('/api/v1/ai/generate-variations', {
      ...form,
      count: 3,
    });

    if (response.data.success) {
      // Show first variation
      const firstVariation = response.data.data[0];
      generatedContent.value = firstVariation.content;
      hashtags.value = firstVariation.hashtags || [];
      bestTime.value = firstVariation.best_time;
    }
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Erreur lors de la génération des variations';
  } finally {
    loading.value = false;
  }
};

const regenerate = () => {
  generateContent();
};

const useContent = () => {
  // Emit event to parent to use this content
  console.log('Using content:', generatedContent.value);
  // In a real app, this would save to a social post draft
  alert('Contenu prêt à être utilisé ! (Fonctionnalité à implémenter)');
};

const copyToClipboard = async () => {
  try {
    await navigator.clipboard.writeText(generatedContent.value);
    alert('Contenu copié dans le presse-papiers !');
  } catch (err) {
    console.error('Failed to copy:', err);
  }
};
</script>

<style scoped>
.input {
  @apply w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent;
}

.btn-sm {
  @apply px-3 py-1.5 text-sm;
}
</style>
