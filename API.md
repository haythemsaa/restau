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
