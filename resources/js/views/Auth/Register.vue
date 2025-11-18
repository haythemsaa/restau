<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="max-w-md w-full">
      <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-gray-900">RestauBoost</h1>
        <p class="mt-2 text-gray-600">Créer votre compte</p>
      </div>

      <div class="card">
        <form @submit.prevent="handleRegister" class="space-y-6">
          <div v-if="authStore.error" class="p-4 bg-red-50 border border-red-200 rounded-lg">
            <p class="text-red-600 text-sm">{{ authStore.error }}</p>
          </div>

          <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
              Nom complet
            </label>
            <input
              id="name"
              v-model="form.name"
              type="text"
              required
              class="input"
              placeholder="Votre nom"
            />
          </div>

          <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
              Email
            </label>
            <input
              id="email"
              v-model="form.email"
              type="email"
              required
              class="input"
              placeholder="votre@email.com"
            />
          </div>

          <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
              Mot de passe
            </label>
            <input
              id="password"
              v-model="form.password"
              type="password"
              required
              class="input"
              placeholder="••••••••"
            />
            <p class="mt-1 text-xs text-gray-500">
              Minimum 8 caractères, avec majuscules, chiffres et symboles
            </p>
          </div>

          <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
              Confirmer le mot de passe
            </label>
            <input
              id="password_confirmation"
              v-model="form.password_confirmation"
              type="password"
              required
              class="input"
              placeholder="••••••••"
            />
          </div>

          <button
            type="submit"
            :disabled="authStore.loading"
            class="w-full btn btn-primary"
          >
            {{ authStore.loading ? 'Création...' : "S'inscrire" }}
          </button>
        </form>

        <div class="mt-6 text-center">
          <router-link to="/login" class="text-sm text-primary-600 hover:text-primary-700">
            Déjà un compte ? Se connecter
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { reactive } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

const router = useRouter();
const authStore = useAuthStore();

const form = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
});

async function handleRegister() {
  const success = await authStore.register(form);
  if (success) {
    router.push('/dashboard');
  }
}
</script>
