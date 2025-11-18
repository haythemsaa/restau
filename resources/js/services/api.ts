import axios, { AxiosInstance } from 'axios';
import type {
  User,
  Business,
  Review,
  SocialPost,
  Conversation,
  AuthResponse,
  ApiResponse,
  PaginatedResponse
} from '@/types';

class ApiService {
  private client: AxiosInstance;

  constructor() {
    this.client = axios.create({
      baseURL: '/api',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
    });

    // Add auth token to requests
    this.client.interceptors.request.use((config) => {
      const token = localStorage.getItem('auth_token');
      if (token) {
        config.headers.Authorization = `Bearer ${token}`;
      }
      return config;
    });

    // Handle 401 errors
    this.client.interceptors.response.use(
      (response) => response,
      (error) => {
        if (error.response?.status === 401) {
          localStorage.removeItem('auth_token');
          window.location.href = '/login';
        }
        return Promise.reject(error);
      }
    );
  }

  // Auth endpoints
  async register(data: { name: string; email: string; password: string; password_confirmation: string }) {
    const response = await this.client.post<AuthResponse>('/auth/register', data);
    return response.data;
  }

  async login(credentials: { email: string; password: string; remember?: boolean }) {
    const response = await this.client.post<AuthResponse>('/auth/login', credentials);
    return response.data;
  }

  async logout() {
    const response = await this.client.post('/auth/logout');
    return response.data;
  }

  async me() {
    const response = await this.client.get<ApiResponse<User>>('/auth/me');
    return response.data;
  }

  // Business endpoints
  async getBusinesses(params?: { page?: number; per_page?: number; status?: string }) {
    const response = await this.client.get<PaginatedResponse<Business>>('/v1/businesses', { params });
    return response.data;
  }

  async getBusiness(id: string) {
    const response = await this.client.get<ApiResponse<Business>>(`/v1/businesses/${id}`);
    return response.data;
  }

  async createBusiness(data: Partial<Business>) {
    const response = await this.client.post<ApiResponse<Business>>('/v1/businesses', data);
    return response.data;
  }

  async updateBusiness(id: string, data: Partial<Business>) {
    const response = await this.client.put<ApiResponse<Business>>(`/v1/businesses/${id}`, data);
    return response.data;
  }

  async deleteBusiness(id: string) {
    const response = await this.client.delete(`/v1/businesses/${id}`);
    return response.data;
  }

  // Review endpoints
  async getReviews(params?: { business_id?: string; platform?: string; rating?: number; page?: number }) {
    const response = await this.client.get<PaginatedResponse<Review>>('/v1/reviews', { params });
    return response.data;
  }

  async getReview(id: string) {
    const response = await this.client.get<ApiResponse<Review>>(`/v1/reviews/${id}`);
    return response.data;
  }

  async createReview(data: Partial<Review>) {
    const response = await this.client.post<ApiResponse<Review>>('/v1/reviews', data);
    return response.data;
  }

  async replyToReview(id: string, reply: string) {
    const response = await this.client.put<ApiResponse<Review>>(`/v1/reviews/${id}`, { reply });
    return response.data;
  }

  // Social Post endpoints
  async getSocialPosts(params?: { business_id?: string; status?: string; page?: number }) {
    const response = await this.client.get<PaginatedResponse<SocialPost>>('/v1/social-posts', { params });
    return response.data;
  }

  async getSocialPost(id: string) {
    const response = await this.client.get<ApiResponse<SocialPost>>(`/v1/social-posts/${id}`);
    return response.data;
  }

  async createSocialPost(data: Partial<SocialPost>) {
    const response = await this.client.post<ApiResponse<SocialPost>>('/v1/social-posts', data);
    return response.data;
  }

  async updateSocialPost(id: string, data: Partial<SocialPost>) {
    const response = await this.client.put<ApiResponse<SocialPost>>(`/v1/social-posts/${id}`, data);
    return response.data;
  }

  async publishSocialPost(id: string) {
    const response = await this.client.post<ApiResponse<SocialPost>>(`/v1/social-posts/${id}/publish`);
    return response.data;
  }

  async deleteSocialPost(id: string) {
    const response = await this.client.delete(`/v1/social-posts/${id}`);
    return response.data;
  }

  // Conversation endpoints
  async getConversations(params?: { business_id?: string; status?: string; page?: number }) {
    const response = await this.client.get<PaginatedResponse<Conversation>>('/v1/conversations', { params });
    return response.data;
  }

  async getConversation(id: string) {
    const response = await this.client.get<ApiResponse<Conversation>>(`/v1/conversations/${id}`);
    return response.data;
  }

  async sendMessage(conversationId: string, content: Record<string, any>) {
    const response = await this.client.post(`/v1/conversations/${conversationId}/messages`, { content });
    return response.data;
  }
}

export const api = new ApiService();
