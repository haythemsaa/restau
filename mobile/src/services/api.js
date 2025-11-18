/**
 * API Service
 * Handles all API requests to Laravel backend
 */

import axios from 'axios';
import AsyncStorage from '@react-native-async-storage/async-storage';

// API Base URL - Change this to your Laravel backend URL
const API_BASE_URL = 'http://10.0.2.2:8000/api'; // Android Emulator
// const API_BASE_URL = 'http://localhost:8000/api'; // iOS Simulator
// const API_BASE_URL = 'https://your-domain.com/api'; // Production

const api = axios.create({
  baseURL: API_BASE_URL,
  timeout: 30000,
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
  },
});

// Request interceptor - Add auth token
api.interceptors.request.use(
  async config => {
    const token = await AsyncStorage.getItem('auth_token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  error => {
    return Promise.reject(error);
  },
);

// Response interceptor - Handle errors
api.interceptors.response.use(
  response => response,
  async error => {
    if (error.response?.status === 401) {
      // Token expired, logout user
      await AsyncStorage.removeItem('auth_token');
      await AsyncStorage.removeItem('user');
    }
    return Promise.reject(error);
  },
);

// ===========================
// AUTH ENDPOINTS
// ===========================

export const authApi = {
  login: async (email, password) => {
    const response = await api.post('/auth/login', {email, password});
    return response.data;
  },

  register: async data => {
    const response = await api.post('/auth/register', data);
    return response.data;
  },

  logout: async () => {
    const response = await api.post('/auth/logout');
    return response.data;
  },

  me: async () => {
    const response = await api.get('/auth/me');
    return response.data;
  },
};

// ===========================
// DASHBOARD ENDPOINTS
// ===========================

export const dashboardApi = {
  getStats: async () => {
    const response = await api.get('/v1/dashboard/stats');
    return response.data;
  },

  getRecentActivity: async () => {
    const response = await api.get('/v1/dashboard/activity');
    return response.data;
  },
};

// ===========================
// CUSTOMERS ENDPOINTS
// ===========================

export const customersApi = {
  getAll: async params => {
    const response = await api.get('/v1/customers', {params});
    return response.data;
  },

  getById: async id => {
    const response = await api.get(`/v1/customers/${id}?include_rfm=1`);
    return response.data;
  },

  create: async data => {
    const response = await api.post('/v1/customers', data);
    return response.data;
  },

  update: async (id, data) => {
    const response = await api.put(`/v1/customers/${id}`, data);
    return response.data;
  },

  delete: async id => {
    const response = await api.delete(`/v1/customers/${id}`);
    return response.data;
  },

  getVips: async () => {
    const response = await api.get('/v1/customers-vips');
    return response.data;
  },

  getAtRisk: async () => {
    const response = await api.get('/v1/customers-at-risk');
    return response.data;
  },

  getBirthdays: async () => {
    const response = await api.get('/v1/customers-birthdays');
    return response.data;
  },

  getSegments: async () => {
    const response = await api.get('/v1/customers-segments');
    return response.data;
  },
};

// ===========================
// AI ENDPOINTS
// ===========================

export const aiApi = {
  generateContent: async data => {
    const response = await api.post('/v1/ai/generate-content', data);
    return response.data;
  },

  generateVariations: async data => {
    const response = await api.post('/v1/ai/generate-variations', data);
    return response.data;
  },

  suggestHashtags: async data => {
    const response = await api.post('/v1/ai/suggest-hashtags', data);
    return response.data;
  },

  analyzeSentiment: async text => {
    const response = await api.post('/v1/ai/analyze-sentiment', {text});
    return response.data;
  },

  analyzeReview: async reviewId => {
    const response = await api.post(`/v1/ai/reviews/${reviewId}/analyze`);
    return response.data;
  },

  generateReviewResponse: async (reviewId, tone) => {
    const response = await api.post(
      `/v1/ai/reviews/${reviewId}/generate-response`,
      {tone},
    );
    return response.data;
  },

  suggestReviewResponses: async (reviewId, count = 3) => {
    const response = await api.post(
      `/v1/ai/reviews/${reviewId}/suggest-responses`,
      {count},
    );
    return response.data;
  },
};

// ===========================
// CAMPAIGNS ENDPOINTS
// ===========================

export const campaignsApi = {
  getAll: async params => {
    const response = await api.get('/v1/campaigns', {params});
    return response.data;
  },

  getById: async id => {
    const response = await api.get(`/v1/campaigns/${id}`);
    return response.data;
  },

  create: async data => {
    const response = await api.post('/v1/campaigns', data);
    return response.data;
  },

  update: async (id, data) => {
    const response = await api.put(`/v1/campaigns/${id}`, data);
    return response.data;
  },

  delete: async id => {
    const response = await api.delete(`/v1/campaigns/${id}`);
    return response.data;
  },

  send: async id => {
    const response = await api.post(`/v1/campaigns/${id}/send`);
    return response.data;
  },
};

// ===========================
// REVIEWS ENDPOINTS
// ===========================

export const reviewsApi = {
  getAll: async params => {
    const response = await api.get('/v1/reviews', {params});
    return response.data;
  },

  getById: async id => {
    const response = await api.get(`/v1/reviews/${id}`);
    return response.data;
  },

  reply: async (id, reply) => {
    const response = await api.put(`/v1/reviews/${id}`, {reply});
    return response.data;
  },
};

export default api;
