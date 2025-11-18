export interface User {
  id: string;
  name: string;
  email: string;
  role: 'admin' | 'manager' | 'agent' | 'viewer';
  email_verified_at?: string;
  last_login_at?: string;
  businesses?: Business[];
}

export interface Business {
  id: string;
  name: string;
  type: string;
  phone?: string;
  email?: string;
  website?: string;
  address: {
    street?: string;
    city?: string;
    postal_code?: string;
    country?: string;
  };
  timezone: string;
  status: 'active' | 'inactive' | 'suspended';
  subscription_tier?: string;
  metadata?: Record<string, any>;
  created_at: string;
  updated_at: string;
}

export interface Review {
  id: string;
  business_id: string;
  platform: 'google' | 'facebook' | 'tripadvisor' | 'yelp';
  platform_review_id: string;
  author_name: string;
  author_photo?: string;
  rating: number;
  text?: string;
  reply?: string;
  replied_at?: string;
  sentiment_score?: number;
  categories?: string[];
  published_at: string;
  created_at: string;
  updated_at: string;
}

export interface SocialPost {
  id: string;
  business_id: string;
  content: string;
  platforms: string[];
  media_urls?: string[];
  scheduled_for?: string;
  published_at?: string;
  status: 'draft' | 'scheduled' | 'publishing' | 'published' | 'failed';
  metrics?: Record<string, any>;
  created_by: string;
  created_at: string;
  updated_at: string;
}

export interface Conversation {
  id: string;
  business_id: string;
  platform: 'google' | 'facebook' | 'instagram' | 'whatsapp' | 'messenger';
  platform_conversation_id: string;
  customer_name?: string;
  customer_email?: string;
  customer_phone?: string;
  status: 'open' | 'pending' | 'resolved' | 'closed';
  assigned_to?: string;
  last_message_at?: string;
  messages?: Message[];
  created_at: string;
  updated_at: string;
}

export interface Message {
  id: string;
  conversation_id: string;
  direction: 'inbound' | 'outbound';
  content: Record<string, any>;
  sent_by?: string;
  read_at?: string;
  created_at: string;
}

export interface AuthResponse {
  message: string;
  user: User;
  token: string;
}

export interface ApiResponse<T> {
  data: T;
  message?: string;
}

export interface PaginatedResponse<T> {
  data: T[];
  meta: {
    current_page: number;
    from: number;
    last_page: number;
    per_page: number;
    to: number;
    total: number;
  };
  links: {
    first: string;
    last: string;
    prev?: string;
    next?: string;
  };
}
