<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="max-w-md w-full">
      <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-gray-900">RestauBoost</h1>
        <p class="mt-2 text-gray-600">Connexion à votre compte</p>
      </div>

      <div class="card">
        <form @submit.prevent="handleLogin" class="space-y-6">
          <div v-if="authStore.error" class="p-4 bg-red-50 border border-red-200 rounded-lg">
            <p class="text-red-600 text-sm">{{ authStore.error }}</p>
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
          </div>

          <div class="flex items-center">
            <input
              id="remember"
              v-model="form.remember"
              type="checkbox"
              class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
            />
            <label for="remember" class="ml-2 block text-sm text-gray-700">
              Se souvenir de moi
            </label>
          </div>

          <button
            type="submit"
            :disabled="authStore.loading"
            class="w-full btn btn-primary"
          >
            {{ authStore.loading ? 'Connexion...' : 'Se connecter' }}
          </button>
        </form>

        <div class="mt-6 text-center">
          <router-link to="/register" class="text-sm text-primary-600 hover:text-primary-700">
            Pas encore de compte ? S'inscrire
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { reactive } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

const router = useRouter();
const route = useRoute();
const authStore = useAuthStore();

const form = reactive({
  email: '',
  password: '',
  remember: false,
});

async function handleLogin() {
  const success = await authStore.login(form);
  if (success) {
    const redirect = route.query.redirect as string || '/dashboard';
    router.push(redirect);
  }
}
</script>
