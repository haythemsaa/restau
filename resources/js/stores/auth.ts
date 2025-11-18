import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import { api } from '@/services/api';
import type { User } from '@/types';

export const useAuthStore = defineStore('auth', () => {
  const user = ref<User | null>(null);
  const token = ref<string | null>(localStorage.getItem('auth_token'));
  const loading = ref(false);
  const error = ref<string | null>(null);

  const isAuthenticated = computed(() => !!token.value && !!user.value);
  const isAdmin = computed(() => user.value?.role === 'admin');
  const isManager = computed(() => user.value?.role === 'manager' || isAdmin.value);

  async function login(credentials: { email: string; password: string; remember?: boolean }) {
    loading.value = true;
    error.value = null;

    try {
      const response = await api.login(credentials);
      token.value = response.token;
      user.value = response.user;
      localStorage.setItem('auth_token', response.token);
      return true;
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Login failed';
      return false;
    } finally {
      loading.value = false;
    }
  }

  async function register(data: {
    name: string;
    email: string;
    password: string;
    password_confirmation: string;
  }) {
    loading.value = true;
    error.value = null;

    try {
      const response = await api.register(data);
      token.value = response.token;
      user.value = response.user;
      localStorage.setItem('auth_token', response.token);
      return true;
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Registration failed';
      return false;
    } finally {
      loading.value = false;
    }
  }

  async function logout() {
    loading.value = true;

    try {
      await api.logout();
    } catch (err) {
      console.error('Logout error:', err);
    } finally {
      token.value = null;
      user.value = null;
      localStorage.removeItem('auth_token');
      loading.value = false;
    }
  }

  async function fetchUser() {
    if (!token.value) return;

    loading.value = true;
    error.value = null;

    try {
      const response = await api.me();
      user.value = response.data;
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Failed to fetch user';
      // If fetching user fails, clear auth
      token.value = null;
      user.value = null;
      localStorage.removeItem('auth_token');
    } finally {
      loading.value = false;
    }
  }

  return {
    user,
    token,
    loading,
    error,
    isAuthenticated,
    isAdmin,
    isManager,
    login,
    register,
    logout,
    fetchUser,
  };
});
