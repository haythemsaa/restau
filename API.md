# RestauBoost API Documentation

API REST pour la plateforme RestauBoost - Solution SaaS de marketing pour restaurants.

## Base URL

```
http://localhost/api
```

## Authentication

L'API utilise Laravel Sanctum pour l'authentification via tokens Bearer.

### Register

Créer un nouveau compte utilisateur.

**Endpoint:** `POST /auth/register`

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "SecureP@ss123",
  "password_confirmation": "SecureP@ss123"
}
```

**Response:** `201 Created`
```json
{
  "message": "User registered successfully",
  "user": {
    "id": "9d3e5b8c-...",
    "name": "John Doe",
    "email": "john@example.com",
    "role": "viewer"
  },
  "token": "1|abc123..."
}
```

### Login

Se connecter et obtenir un token d'authentification.

**Endpoint:** `POST /auth/login`

**Request Body:**
```json
{
  "email": "john@example.com",
  "password": "SecureP@ss123",
  "remember": true
}
```

**Response:** `200 OK`
```json
{
  "message": "Login successful",
  "user": {
    "id": "9d3e5b8c-...",
    "name": "John Doe",
    "email": "john@example.com",
    "role": "admin"
  },
  "token": "2|def456..."
}
```

### Logout

Se déconnecter et révoquer le token actuel.

**Endpoint:** `POST /auth/logout`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:** `200 OK`
```json
{
  "message": "Logged out successfully"
}
```

### Get Current User

Récupérer les informations de l'utilisateur connecté.

**Endpoint:** `GET /auth/me`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:** `200 OK`
```json
{
  "data": {
    "id": "9d3e5b8c-...",
    "name": "John Doe",
    "email": "john@example.com",
    "role": "admin",
    "businesses": [...]
  }
}
```

---

## Businesses

Gestion des établissements (restaurants).

**Note:** Tous les endpoints nécessitent l'authentification (`Authorization: Bearer {token}`).

### List Businesses

**Endpoint:** `GET /v1/businesses`

**Query Parameters:**
- `page` (optional): Page number for pagination
- `per_page` (optional): Items per page (default: 15)
- `status` (optional): Filter by status (active, inactive, suspended)

**Response:** `200 OK`
```json
{
  "data": [
    {
      "id": "9d3e5b8c-...",
      "name": "Le Petit Bistrot",
      "type": "restaurant",
      "phone": "+33123456789",
      "email": "contact@petitbistrot.fr",
      "website": "https://petitbistrot.fr",
      "address": {
        "street": "123 Rue de Paris",
        "city": "Paris",
        "postal_code": "75001",
        "country": "France"
      },
      "timezone": "Europe/Paris",
      "status": "active",
      "subscription_tier": "pro",
      "created_at": "2024-11-18T10:00:00Z",
      "updated_at": "2024-11-18T10:00:00Z"
    }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 67
  }
}
```

### Get Business

**Endpoint:** `GET /v1/businesses/{id}`

**Response:** `200 OK`
```json
{
  "data": {
    "id": "9d3e5b8c-...",
    "name": "Le Petit Bistrot",
    "type": "restaurant",
    ...
  }
}
```

### Create Business

**Endpoint:** `POST /v1/businesses`

**Request Body:**
```json
{
  "name": "Le Petit Bistrot",
  "type": "restaurant",
  "phone": "+33123456789",
  "email": "contact@petitbistrot.fr",
  "website": "https://petitbistrot.fr",
  "address": {
    "street": "123 Rue de Paris",
    "city": "Paris",
    "postal_code": "75001",
    "country": "France"
  },
  "timezone": "Europe/Paris",
  "status": "active"
}
```

**Response:** `201 Created`

### Update Business

**Endpoint:** `PUT /v1/businesses/{id}`

**Request Body:** (partial updates supported)
```json
{
  "name": "Le Nouveau Bistrot",
  "status": "active"
}
```

**Response:** `200 OK`

### Delete Business

**Endpoint:** `DELETE /v1/businesses/{id}`

**Response:** `204 No Content`

---

## Reviews

Gestion des avis clients.

### List Reviews

**Endpoint:** `GET /v1/reviews`

**Query Parameters:**
- `business_id` (optional): Filter by business
- `platform` (optional): Filter by platform (google, facebook, tripadvisor, yelp)
- `rating` (optional): Filter by rating (0-5)
- `page` (optional): Page number

**Response:** `200 OK`
```json
{
  "data": [
    {
      "id": "9d3e5b8c-...",
      "business_id": "9d3e5b8c-...",
      "platform": "google",
      "platform_review_id": "ChZDSUhNMG9nS0VJQ0FnSURycGFfTEN3",
      "author_name": "Marie Dupont",
      "author_photo": "https://...",
      "rating": 4.5,
      "text": "Excellent restaurant, très bon accueil !",
      "reply": "Merci pour votre retour !",
      "replied_at": "2024-11-18T12:00:00Z",
      "sentiment_score": 0.85,
      "categories": ["Service+", "Nourriture+"],
      "published_at": "2024-11-15T10:00:00Z",
      "created_at": "2024-11-15T10:05:00Z"
    }
  ]
}
```

### Create Review

**Endpoint:** `POST /v1/reviews`

**Request Body:**
```json
{
  "business_id": "9d3e5b8c-...",
  "platform": "google",
  "platform_review_id": "ChZDSUhNMG9nS0VJQ0FnSURycGFfTEN3",
  "author_name": "Marie Dupont",
  "rating": 4.5,
  "text": "Excellent restaurant !",
  "published_at": "2024-11-15T10:00:00Z"
}
```

**Response:** `201 Created`

### Reply to Review

**Endpoint:** `PUT /v1/reviews/{id}`

**Request Body:**
```json
{
  "reply": "Merci pour votre retour !"
}
```

**Response:** `200 OK`

---

## Social Posts

Gestion des publications sur les réseaux sociaux.

### List Social Posts

**Endpoint:** `GET /v1/social-posts`

**Query Parameters:**
- `business_id` (optional): Filter by business
- `status` (optional): Filter by status (draft, scheduled, publishing, published, failed)
- `page` (optional): Page number

**Response:** `200 OK`
```json
{
  "data": [
    {
      "id": "9d3e5b8c-...",
      "business_id": "9d3e5b8c-...",
      "content": "Découvrez notre nouveau menu de saison !",
      "platforms": ["facebook", "instagram"],
      "media_urls": [
        "https://storage.example.com/image1.jpg"
      ],
      "scheduled_for": "2024-11-20T12:00:00Z",
      "published_at": null,
      "status": "scheduled",
      "metrics": null,
      "created_by": "9d3e5b8c-...",
      "created_at": "2024-11-18T10:00:00Z"
    }
  ]
}
```

### Create Social Post

**Endpoint:** `POST /v1/social-posts`

**Request Body:**
```json
{
  "business_id": "9d3e5b8c-...",
  "content": "Découvrez notre nouveau menu !",
  "platforms": ["facebook", "instagram"],
  "media_urls": ["https://example.com/image.jpg"],
  "status": "draft"
}
```

**Response:** `201 Created`

### Update Social Post

**Endpoint:** `PUT /v1/social-posts/{id}`

**Request Body:**
```json
{
  "content": "Contenu mis à jour",
  "status": "scheduled",
  "scheduled_for": "2024-11-20T12:00:00Z"
}
```

**Response:** `200 OK`

### Publish Social Post

Publier immédiatement un post sur les plateformes configurées.

**Endpoint:** `POST /v1/social-posts/{id}/publish`

**Response:** `200 OK`
```json
{
  "data": {
    "id": "9d3e5b8c-...",
    "status": "publishing",
    ...
  }
}
```

### Delete Social Post

**Endpoint:** `DELETE /v1/social-posts/{id}`

**Response:** `204 No Content`

---

## Conversations

Gestion de la messagerie unifiée.

### List Conversations

**Endpoint:** `GET /v1/conversations`

**Query Parameters:**
- `business_id` (optional): Filter by business
- `platform` (optional): Filter by platform
- `status` (optional): Filter by status (open, pending, resolved, closed)
- `page` (optional): Page number

**Response:** `200 OK`
```json
{
  "data": [
    {
      "id": "9d3e5b8c-...",
      "business_id": "9d3e5b8c-...",
      "platform": "messenger",
      "platform_conversation_id": "t_123456789",
      "customer_name": "Jean Martin",
      "customer_email": "jean@example.com",
      "status": "open",
      "assigned_to": "9d3e5b8c-...",
      "last_message_at": "2024-11-18T14:30:00Z",
      "messages": [...],
      "created_at": "2024-11-18T10:00:00Z"
    }
  ]
}
```

### Get Conversation

**Endpoint:** `GET /v1/conversations/{id}`

**Response:** `200 OK` (includes messages)

### Send Message

**Endpoint:** `POST /v1/conversations/{id}/messages`

**Request Body:**
```json
{
  "content": {
    "text": "Bonjour, comment puis-je vous aider ?"
  }
}
```

**Response:** `201 Created`

---

## Error Responses

L'API retourne des codes HTTP standards et des messages d'erreur en JSON.

### 400 Bad Request
```json
{
  "message": "Invalid request data"
}
```

### 401 Unauthorized
```json
{
  "message": "Unauthenticated."
}
```

### 403 Forbidden
```json
{
  "message": "This action is unauthorized."
}
```

### 404 Not Found
```json
{
  "message": "Resource not found"
}
```

### 422 Unprocessable Entity
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["The email field is required."],
    "password": ["The password must be at least 8 characters."]
  }
}
```

### 500 Internal Server Error
```json
{
  "message": "Server Error"
}
```

---

## Rate Limiting

L'API applique les limites suivantes:
- **Authentification:** 5 tentatives par minute
- **API générale:** 60 requêtes par minute par utilisateur

Headers de réponse:
```
X-RateLimit-Limit: 60
X-RateLimit-Remaining: 59
```

---

## Pagination

Les endpoints de liste retournent des données paginées avec les méta-données suivantes:

```json
{
  "data": [...],
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 5,
    "per_page": 15,
    "to": 15,
    "total": 67
  },
  "links": {
    "first": "http://localhost/api/v1/businesses?page=1",
    "last": "http://localhost/api/v1/businesses?page=5",
    "prev": null,
    "next": "http://localhost/api/v1/businesses?page=2"
  }
}
```

---

## Versioning

L'API utilise le versioning dans l'URL:
- Current version: `/api/v1`
- Future versions: `/api/v2`, etc.

---

## Support

Pour toute question ou problème, consultez:
- Repository: https://github.com/haythemsaa/restau
- Documentation: README.md

---

## IA - Intelligence Artificielle 🤖

### Génération de Contenu

#### Generate Social Media Content

Génère du contenu optimisé pour les réseaux sociaux avec l'IA.

**Endpoint:** `POST /v1/ai/generate-content`

**Request Body:**
```json
{
  "business_id": "9d3e5b8c-...",
  "platform": "instagram",
  "theme": "Nouveau menu d'automne",
  "tone": "friendly",
  "audience": "food lovers",
  "details": "Plats à base de produits de saison"
}
```

**Response:** `200 OK`
```json
{
  "success": true,
  "data": {
    "content": "🍂 L'automne arrive chez [Restaurant]!\n\nDécouvrez notre nouveau menu...",
    "hashtags": ["automne", "cuisine", "produitsdeSaison"],
    "word_count": 145,
    "best_time": {
      "best_days": ["Mardi", "Mercredi", "Jeudi"],
      "best_hours": ["11:00-13:00", "19:00-21:00"]
    }
  }
}
```

#### Generate Content Variations

Génère plusieurs variations de contenu.

**Endpoint:** `POST /v1/ai/generate-variations`

**Parameters:** Même que `/generate-content` + `count` (1-5)

#### Suggest Hashtags

Suggère des hashtags pertinents.

**Endpoint:** `POST /v1/ai/suggest-hashtags`

**Request Body:**
```json
{
  "content": "Découvrez notre nouveau menu...",
  "industry": "restaurant"
}
```

---

### Analyse de Sentiment

#### Analyze Text Sentiment

Analyse le sentiment d'un texte.

**Endpoint:** `POST /v1/ai/analyze-sentiment`

**Request Body:**
```json
{
  "text": "Excellent restaurant, très bon accueil!"
}
```

**Response:** `200 OK`
```json
{
  "success": true,
  "data": {
    "overall_sentiment": {
      "score": 0.85,
      "label": "Très Positif"
    },
    "aspects": {
      "food": {"score": 0.9, "mentions": ["excellent"]},
      "service": {"score": 0.8, "mentions": ["bon accueil"]}
    },
    "emotions": ["joy", "satisfaction"],
    "insights": ["Point fort: service", "Client très satisfait"],
    "priority": "LOW",
    "recommended_action": "Répondre dans la semaine..."
  }
}
```

#### Analyze Review

Analyse complète d'un avis avec sauvegarde en DB.

**Endpoint:** `POST /v1/ai/reviews/{review}/analyze`

**Response:** Même structure que ci-dessus avec `review_id`

---

### Réponses Automatiques aux Avis

#### Generate Review Response

Génère une réponse professionnelle à un avis.

**Endpoint:** `POST /v1/ai/reviews/{review}/generate-response`

**Response:** `200 OK`
```json
{
  "success": true,
  "data": {
    "response": "Bonjour Marie,\n\nMerci infiniment pour votre retour...",
    "word_count": 87
  }
}
```

#### Suggest Multiple Responses

Génère 3 suggestions de réponses avec des tons différents.

**Endpoint:** `POST /v1/ai/reviews/{review}/suggest-responses`

**Response:** `200 OK`
```json
{
  "success": true,
  "data": {
    "suggestions": [
      {
        "id": 1,
        "content": "...",
        "tone": "professional",
        "length": 95
      },
      {
        "id": 2,
        "content": "...",
        "tone": "warm",
        "length": 102
      },
      {
        "id": 3,
        "content": "...",
        "tone": "enthusiastic",
        "length": 89
      }
    ],
    "review": {
      "id": "...",
      "rating": 5,
      "text": "...",
      "author": "Marie Dupont"
    }
  }
}
```

#### Auto-Reply to Review

Publie automatiquement une réponse générée par IA.

**Endpoint:** `POST /v1/ai/reviews/{review}/auto-reply`

**Request Body (optional):**
```json
{
  "custom_message": "Message personnalisé si vous ne voulez pas l'IA"
}
```

**Response:** `200 OK`
```json
{
  "success": true,
  "message": "Response posted successfully",
  "data": {
    "review_id": "...",
    "reply": "...",
    "replied_at": "2024-11-18T15:30:00Z"
  }
}
```

---

## CRM - Gestion de la Clientèle 👥

### Customers

#### List Customers

**Endpoint:** `GET /v1/customers`

**Query Parameters:**
- `tier` (optional): Filter by tier (regular, vip, super_vip)
- `segment_id` (optional): Filter by segment
- `vip_only` (optional): Boolean, show only VIP customers
- `at_risk_only` (optional): Boolean, show only at-risk customers
- `search` (optional): Search in name/email
- `page`, `per_page`: Pagination

**Response:** `200 OK`
```json
{
  "data": [
    {
      "id": "...",
      "email": "jean@example.com",
      "first_name": "Jean",
      "last_name": "Martin",
      "full_name": "Jean Martin",
      "birth_date": "1985-06-15",
      "tier": "vip",
      "lifetime_value": 1250.50,
      "visit_count": 15,
      "last_visit_at": "2024-11-10T19:30:00Z",
      "average_spend": 83.37,
      "is_vip": true,
      "segments": [...]
    }
  ],
  "meta": {...}
}
```

#### Get Customer Details

**Endpoint:** `GET /v1/customers/{id}`

**Response:** `200 OK`
```json
{
  "data": {
    "id": "...",
    "email": "jean@example.com",
    ...
  },
  "stats": {
    "rfm_score": {
      "recency": 5,
      "frequency": 4,
      "monetary": 5,
      "total_score": 4.67,
      "segment": "Champions"
    },
    "average_spend": 83.37,
    "is_vip": true,
    "at_risk": false,
    "is_birthday": false
  }
}
```

#### Create Customer

**Endpoint:** `POST /v1/customers`

**Request Body:**
```json
{
  "email": "new@customer.com",
  "phone": "+33123456789",
  "first_name": "Sophie",
  "last_name": "Dubois",
  "birth_date": "1990-03-20",
  "preferences": {
    "dietary": ["vegetarian"],
    "allergies": []
  },
  "tags": ["food-blogger"],
  "language": "fr"
}
```

#### Update Customer

**Endpoint:** `PUT /v1/customers/{id}`

#### Delete Customer

**Endpoint:** `DELETE /v1/customers/{id}`

---

### Customer Segments

#### Get All Segments

**Endpoint:** `GET /v1/customers-segments`

**Response:** `200 OK`
```json
{
  "data": [
    {
      "id": "...",
      "name": "VIP",
      "description": "High-value loyal customers",
      "criteria": {
        "lifetime_value_min": 500,
        "visit_count_min": 10
      },
      "auto_update": true,
      "customer_count": 47
    }
  ]
}
```

#### Get At-Risk Customers

Clients qui n'ont pas visité depuis 60+ jours.

**Endpoint:** `GET /v1/customers-at-risk`

#### Get VIP Customers

Clients VIP et Super VIP triés par valeur.

**Endpoint:** `GET /v1/customers-vips`

#### Get Birthday Customers

Clients dont c'est l'anniversaire ce mois-ci.

**Endpoint:** `GET /v1/customers-birthdays`

---

## Nouvelles Fonctionnalités IA - Résumé

### 🎨 Génération de Contenu
- ✅ Contenu social media optimisé par plateforme
- ✅ Suggestions de hashtags intelligentes
- ✅ Meilleurs moments de publication
- ✅ Multiple variations d'un même thème

### 🧠 Analyse de Sentiment
- ✅ Score de sentiment global (-1 à +1)
- ✅ Analyse par aspect (nourriture, service, ambiance, prix)
- ✅ Détection d'émotions (joie, déception, colère, surprise)
- ✅ Insights actionnables automatiques
- ✅ Priorisation automatique (LOW, MEDIUM, HIGH, URGENT)

### 💬 Réponses Automatiques
- ✅ Génération de réponses personnalisées
- ✅ Adaptation du ton selon la note
- ✅ 3 suggestions avec tons différents
- ✅ Validation et contrôle qualité
- ✅ Publication automatique

### 👥 CRM Complet
- ✅ Profils clients unifiés
- ✅ Analyse RFM (Recency, Frequency, Monetary)
- ✅ Segmentation automatique
- ✅ Détection clients à risque
- ✅ Programme VIP automatique
- ✅ Campagnes d'anniversaire
- ✅ Historique des visites

---

## Stack Technique Mis à Jour

**Backend:**
- Laravel 10 + PHP 8.2
- OpenAI GPT-4 API
- PostgreSQL 15
- Redis

**Frontend:**
- Vue.js 3 + TypeScript
- Tailwind CSS
- Pinia (State Management)
- Axios

**IA & ML:**
- OpenAI GPT-4 pour génération
- Analyse sentiment multi-dimensionnelle
- Détection émotions

**Intégrations:**
- Google My Business
- Facebook/Instagram
- Yelp, TripAdvisor
- Email/SMS providers

