import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

const routes = [
  {
    path: '/',
    redirect: '/dashboard',
  },
  {
    path: '/login',
    name: 'login',
    component: () => import('@/views/Auth/Login.vue'),
    meta: { requiresGuest: true },
  },
  {
    path: '/register',
    name: 'register',
    component: () => import('@/views/Auth/Register.vue'),
    meta: { requiresGuest: true },
  },
  {
    path: '/dashboard',
    name: 'dashboard',
    component: () => import('@/views/Dashboard.vue'),
    meta: { requiresAuth: true },
  },
  {
    path: '/businesses',
    name: 'businesses',
    component: () => import('@/views/Businesses/Index.vue'),
    meta: { requiresAuth: true },
  },
  {
    path: '/businesses/:id',
    name: 'business-detail',
    component: () => import('@/views/Businesses/Detail.vue'),
    meta: { requiresAuth: true },
  },
  {
    path: '/reviews',
    name: 'reviews',
    component: () => import('@/views/Reviews/Index.vue'),
    meta: { requiresAuth: true },
  },
  {
    path: '/social-posts',
    name: 'social-posts',
    component: () => import('@/views/SocialPosts/Index.vue'),
    meta: { requiresAuth: true },
  },
  {
    path: '/conversations',
    name: 'conversations',
    component: () => import('@/views/Conversations/Index.vue'),
    meta: { requiresAuth: true },
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// Navigation guards
router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore();

  // If route requires auth and user is not authenticated
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    // Try to fetch user if token exists
    if (authStore.token) {
      await authStore.fetchUser();
      if (authStore.isAuthenticated) {
        return next();
      }
    }
    return next({ name: 'login', query: { redirect: to.fullPath } });
  }

  // If route requires guest (login/register) and user is authenticated
  if (to.meta.requiresGuest && authStore.isAuthenticated) {
    return next({ name: 'dashboard' });
  }

  next();
});

export default router;
