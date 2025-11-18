# RestauBoost API Documentation

Documentation complète de l'API RestauBoost v1.0

## Table des Matières

1. [Introduction](#introduction)
2. [Authentification](#authentification)
3. [Endpoints](#endpoints)
   - [Authentification](#auth-endpoints)
   - [Clients](#clients-endpoints)
   - [Visites](#visits-endpoints)
   - [Segments](#segments-endpoints)
   - [Intelligence Artificielle](#ai-endpoints)
   - [Campagnes Email](#campaigns-endpoints)
   - [Templates Email](#templates-endpoints)
4. [Erreurs](#erreurs)
5. [Rate Limiting](#rate-limiting)

---

## Introduction

### Base URL
```
Production: https://api.restauboost.com/api/v1
Development: http://localhost:8000/api/v1
```

### Format des Réponses
Toutes les réponses sont au format JSON.

**Succès:**
```json
{
  "data": { ... },
  "message": "Success message",
  "meta": { ... }
}
```

**Erreur:**
```json
{
  "error": "Error message",
  "message": "Detailed description",
  "errors": { ... }
}
```

---

## Authentification

### Inscription

**Endpoint:** `POST /auth/register`

**Body:**
```json
{
  "name": "John Doe",
  "email": "john@restaurant.com",
  "password": "password123",
  "password_confirmation": "password123",
  "restaurant_name": "Le Gourmet"
}
```

**Response:** `201 Created`
```json
{
  "data": {
    "user": {
      "id": "uuid",
      "name": "John Doe",
      "email": "john@restaurant.com",
      "created_at": "2024-01-15T10:00:00Z"
    },
    "token": "bearer_token_here"
  },
  "message": "Registration successful"
}
```

### Connexion

**Endpoint:** `POST /auth/login`

**Body:**
```json
{
  "email": "john@restaurant.com",
  "password": "password123"
}
```

**Response:** `200 OK`
```json
{
  "data": {
    "user": {
      "id": "uuid",
      "name": "John Doe",
      "email": "john@restaurant.com"
    },
    "token": "bearer_token_here"
  },
  "message": "Login successful"
}
```

### Déconnexion

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

### Profil Utilisateur

**Endpoint:** `GET /auth/me`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:** `200 OK`
```json
{
  "data": {
    "id": "uuid",
    "name": "John Doe",
    "email": "john@restaurant.com",
    "created_at": "2024-01-15T10:00:00Z"
  }
}
```

---

## Clients Endpoints

### Liste des Clients

**Endpoint:** `GET /customers`

**Headers:**
```
Authorization: Bearer {token}
```

**Query Parameters:**
- `search` (string, optional) - Recherche par nom, email, téléphone
- `tier` (string, optional) - Filtre par tier: `regular`, `vip`, `super_vip`
- `at_risk` (boolean, optional) - Filtre les clients à risque
- `page` (integer, optional, default: 1) - Numéro de page
- `per_page` (integer, optional, default: 15) - Éléments par page
- `sort_by` (string, optional, default: created_at) - Champ de tri
- `sort_order` (string, optional, default: desc) - Ordre: `asc` ou `desc`

**Example:**
```
GET /customers?search=dupont&tier=vip&page=1&per_page=20
```

**Response:** `200 OK`
```json
{
  "data": [
    {
      "id": "uuid",
      "full_name": "Jean Dupont",
      "email": "jean.dupont@email.com",
      "phone": "+33612345678",
      "tier": "vip",
      "visit_count": 24,
      "lifetime_value": "1250.00",
      "days_since_last_visit": 3,
      "at_risk": false,
      "created_at": "2023-06-10T12:00:00Z",
      "last_visit_date": "2024-01-15T19:30:00Z"
    }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 20,
    "total": 95
  }
}
```

### Détails d'un Client

**Endpoint:** `GET /customers/{id}`

**Headers:**
```
Authorization: Bearer {token}
```

**Query Parameters:**
- `include_rfm` (boolean, optional) - Inclure le score RFM
- `include_visits` (boolean, optional) - Inclure l'historique des visites
- `include_preferences` (boolean, optional) - Inclure les préférences

**Response:** `200 OK`
```json
{
  "data": {
    "id": "uuid",
    "full_name": "Jean Dupont",
    "email": "jean.dupont@email.com",
    "phone": "+33612345678",
    "tier": "vip",
    "visit_count": 24,
    "lifetime_value": "1250.00",
    "average_spend": "52.08",
    "days_since_last_visit": 3,
    "at_risk": false,
    "preferences": {
      "dietary": "Sans gluten",
      "allergies": "Fruits de mer",
      "favorite_dish": "Filet mignon"
    },
    "rfm_score": {
      "recency_score": 5,
      "frequency_score": 4,
      "monetary_score": 4,
      "total_score": 13,
      "segment": "Champion"
    },
    "recent_visits": [
      {
        "id": "uuid",
        "visit_date": "2024-01-15T19:30:00Z",
        "total_amount": "85.50",
        "items_ordered": 3,
        "notes": "Anniversaire"
      }
    ],
    "created_at": "2023-06-10T12:00:00Z",
    "updated_at": "2024-01-15T19:30:00Z"
  }
}
```

### Créer un Client

**Endpoint:** `POST /customers`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Body:**
```json
{
  "first_name": "Marie",
  "last_name": "Martin",
  "email": "marie.martin@email.com",
  "phone": "+33687654321",
  "birth_date": "1990-05-15",
  "preferences": {
    "dietary": "Végétarien",
    "allergies": "Arachides",
    "favorite_dish": "Risotto aux champignons"
  },
  "notes": "Cliente fidèle, préfère les tables près de la fenêtre"
}
```

**Response:** `201 Created`
```json
{
  "data": {
    "id": "uuid",
    "full_name": "Marie Martin",
    "email": "marie.martin@email.com",
    "phone": "+33687654321",
    "tier": "regular",
    "visit_count": 0,
    "lifetime_value": "0.00",
    "created_at": "2024-01-15T10:00:00Z"
  },
  "message": "Customer created successfully"
}
```

### Mettre à Jour un Client

**Endpoint:** `PUT /customers/{id}`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Body:**
```json
{
  "first_name": "Marie",
  "last_name": "Martin-Dupuis",
  "phone": "+33687654322",
  "preferences": {
    "dietary": "Végétarien",
    "allergies": "Arachides, Lactose",
    "favorite_dish": "Risotto aux champignons"
  }
}
```

**Response:** `200 OK`
```json
{
  "data": {
    "id": "uuid",
    "full_name": "Marie Martin-Dupuis",
    "email": "marie.martin@email.com",
    "phone": "+33687654322",
    "updated_at": "2024-01-16T14:30:00Z"
  },
  "message": "Customer updated successfully"
}
```

### Supprimer un Client

**Endpoint:** `DELETE /customers/{id}`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:** `200 OK`
```json
{
  "message": "Customer deleted successfully"
}
```

---

## Visites Endpoints

### Liste des Visites

**Endpoint:** `GET /customer-visits`

**Headers:**
```
Authorization: Bearer {token}
```

**Query Parameters:**
- `customer_id` (uuid, optional) - Filtre par client
- `from_date` (date, optional) - Date de début (Y-m-d)
- `to_date` (date, optional) - Date de fin (Y-m-d)
- `page` (integer, optional)
- `per_page` (integer, optional)

**Response:** `200 OK`
```json
{
  "data": [
    {
      "id": "uuid",
      "customer_id": "uuid",
      "customer_name": "Jean Dupont",
      "visit_date": "2024-01-15T19:30:00Z",
      "total_amount": "85.50",
      "items_ordered": 3,
      "notes": "Anniversaire",
      "created_at": "2024-01-15T19:30:00Z"
    }
  ],
  "meta": {
    "current_page": 1,
    "total": 1248
  }
}
```

### Créer une Visite

**Endpoint:** `POST /customer-visits`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Body:**
```json
{
  "customer_id": "uuid",
  "visit_date": "2024-01-15T19:30:00Z",
  "total_amount": 85.50,
  "items_ordered": 3,
  "notes": "Anniversaire - offert le dessert"
}
```

**Response:** `201 Created`
```json
{
  "data": {
    "id": "uuid",
    "customer_id": "uuid",
    "visit_date": "2024-01-15T19:30:00Z",
    "total_amount": "85.50",
    "items_ordered": 3,
    "notes": "Anniversaire - offert le dessert",
    "created_at": "2024-01-15T19:30:00Z"
  },
  "message": "Visit recorded successfully"
}
```

---

## Segments Endpoints

### Liste des Segments

**Endpoint:** `GET /customer-segments`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:** `200 OK`
```json
{
  "data": [
    {
      "id": "uuid",
      "name": "Clients VIP Actifs",
      "description": "Clients VIP ayant visité dans les 30 derniers jours",
      "filter_criteria": {
        "tier": "vip",
        "days_since_last_visit": {"max": 30}
      },
      "customer_count": 42,
      "created_at": "2024-01-01T10:00:00Z"
    }
  ]
}
```

### Créer un Segment

**Endpoint:** `POST /customer-segments`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Body:**
```json
{
  "name": "Grands Dépensiers",
  "description": "Clients avec LTV > 1000€",
  "filter_criteria": {
    "lifetime_value": {"min": 1000}
  }
}
```

**Response:** `201 Created`

---

## Intelligence Artificielle Endpoints

### Générer du Contenu

**Endpoint:** `POST /ai/generate-content`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Body:**
```json
{
  "type": "facebook",
  "prompt": "Nouveau menu automne avec produits locaux",
  "tone": "professional",
  "max_length": 280
}
```

**Types disponibles:** `facebook`, `instagram`, `twitter`, `email`, `blog`
**Tons disponibles:** `professional`, `friendly`, `enthusiastic`, `casual`

**Response:** `200 OK`
```json
{
  "data": {
    "content": "🍽️ Découvrez notre nouveau menu automne!\n\nNous sommes ravis de vous présenter nos plats de saison...",
    "type": "facebook",
    "word_count": 85,
    "character_count": 420,
    "generated_at": "2024-01-15T10:30:00Z"
  },
  "message": "Content generated successfully"
}
```

### Analyser le Sentiment

**Endpoint:** `POST /ai/analyze-sentiment`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Body:**
```json
{
  "text": "Le service était excellent mais le plat principal était un peu froid. Bonne ambiance générale."
}
```

**Response:** `200 OK`
```json
{
  "data": {
    "overall_sentiment": "mixed",
    "sentiment_score": 0.62,
    "aspects": {
      "service": {
        "sentiment": "positive",
        "score": 0.92,
        "keywords": ["excellent"]
      },
      "food": {
        "sentiment": "negative",
        "score": 0.28,
        "keywords": ["froid"]
      },
      "ambiance": {
        "sentiment": "positive",
        "score": 0.75,
        "keywords": ["bonne"]
      }
    },
    "summary": "Avis globalement positif avec quelques points d'amélioration sur la température des plats"
  }
}
```

### Générer une Réponse à un Avis

**Endpoint:** `POST /ai/generate-review-response`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Body:**
```json
{
  "review_text": "Excellent restaurant, service impeccable!",
  "review_rating": 5,
  "tone": "professional"
}
```

**Response:** `200 OK`
```json
{
  "data": {
    "response": "Merci beaucoup pour votre retour chaleureux ! Nous sommes ravis que vous ayez apprécié votre expérience chez nous. Toute l'équipe vous remercie et espère vous revoir très bientôt !",
    "tone": "professional",
    "generated_at": "2024-01-15T11:00:00Z"
  }
}
```

---

## Campagnes Email Endpoints

### Liste des Campagnes

**Endpoint:** `GET /email-campaigns`

**Headers:**
```
Authorization: Bearer {token}
```

**Query Parameters:**
- `status` (string, optional) - Filtre par statut: `draft`, `scheduled`, `sent`, `sending`
- `page` (integer, optional)
- `per_page` (integer, optional)

**Response:** `200 OK`
```json
{
  "data": [
    {
      "id": "uuid",
      "name": "Campagne Bienvenue 2024",
      "email_template_id": "uuid",
      "template_name": "Bienvenue Nouveau Client",
      "status": "scheduled",
      "scheduled_at": "2024-01-20T10:00:00Z",
      "total_recipients": 125,
      "sent_count": 0,
      "opened_count": 0,
      "clicked_count": 0,
      "created_at": "2024-01-15T09:00:00Z"
    }
  ],
  "meta": {
    "current_page": 1,
    "total": 15
  }
}
```

### Créer une Campagne

**Endpoint:** `POST /email-campaigns`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Body:**
```json
{
  "name": "Réactivation Janvier 2024",
  "email_template_id": "uuid",
  "segment_filter": {
    "days_since_last_visit": {"min": 30},
    "visit_count": {"min": 3}
  },
  "scheduled_at": "2024-01-25T14:00:00Z"
}
```

**Response:** `201 Created`
```json
{
  "data": {
    "id": "uuid",
    "name": "Réactivation Janvier 2024",
    "status": "draft",
    "estimated_recipients": 87,
    "created_at": "2024-01-15T12:00:00Z"
  },
  "message": "Campaign created successfully"
}
```

### Envoyer une Campagne

**Endpoint:** `POST /email-campaigns/{id}/send`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:** `200 OK`
```json
{
  "message": "Campaign queued for sending",
  "data": {
    "id": "uuid",
    "status": "sending",
    "total_recipients": 87,
    "estimated_completion": "2024-01-15T13:30:00Z"
  }
}
```

### Statistiques d'une Campagne

**Endpoint:** `GET /email-campaigns/{id}/stats`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:** `200 OK`
```json
{
  "data": {
    "campaign_id": "uuid",
    "total_sent": 87,
    "total_delivered": 85,
    "total_opened": 52,
    "total_clicked": 23,
    "total_bounced": 2,
    "total_unsubscribed": 1,
    "open_rate": 61.18,
    "click_rate": 27.06,
    "bounce_rate": 2.35,
    "unsubscribe_rate": 1.18
  }
}
```

---

## Templates Email Endpoints

### Liste des Templates

**Endpoint:** `GET /email-templates`

**Headers:**
```
Authorization: Bearer {token}
```

**Query Parameters:**
- `category` (string, optional) - Filtre par catégorie

**Response:** `200 OK`
```json
{
  "data": [
    {
      "id": "uuid",
      "name": "Bienvenue Nouveau Client",
      "subject": "Bienvenue chez {{restaurant_name}} !",
      "category": "welcome",
      "variables": ["customer_name", "restaurant_name", "reservation_link"],
      "created_at": "2024-01-01T10:00:00Z"
    }
  ]
}
```

### Créer un Template

**Endpoint:** `POST /email-templates`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Body:**
```json
{
  "name": "Offre Spéciale",
  "subject": "{{offer_name}} - Offre exclusive pour vous !",
  "content": "<html>...</html>",
  "variables": ["customer_name", "offer_name", "expiry_date"],
  "category": "promotional"
}
```

**Response:** `201 Created`

---

## Erreurs

### Codes de Statut HTTP

- `200 OK` - Succès
- `201 Created` - Ressource créée
- `400 Bad Request` - Requête invalide
- `401 Unauthorized` - Non authentifié
- `403 Forbidden` - Non autorisé
- `404 Not Found` - Ressource non trouvée
- `422 Unprocessable Entity` - Validation échouée
- `429 Too Many Requests` - Rate limit dépassé
- `500 Internal Server Error` - Erreur serveur

### Format des Erreurs de Validation

**Status:** `422 Unprocessable Entity`

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": [
      "The email field is required.",
      "The email must be a valid email address."
    ],
    "password": [
      "The password must be at least 8 characters."
    ]
  }
}
```

---

## Rate Limiting

### Limites par Défaut

- **Authentifié:** 60 requêtes par minute
- **Non authentifié:** 20 requêtes par minute

### Headers de Réponse

```
X-RateLimit-Limit: 60
X-RateLimit-Remaining: 55
X-RateLimit-Reset: 1642251600
```

### Dépassement de Limite

**Status:** `429 Too Many Requests`

```json
{
  "message": "Too many requests. Please try again later.",
  "retry_after": 45
}
```

---

## Webhooks (À venir)

### Événements Disponibles

- `customer.created`
- `customer.updated`
- `customer.tier_changed`
- `visit.created`
- `campaign.sent`
- `campaign.opened`
- `campaign.clicked`

---

## Support

**Email:** api-support@restauboost.com
**Documentation:** https://docs.restauboost.com
**Status:** https://status.restauboost.com

---

**Version:** 1.0.0
**Dernière mise à jour:** 2024-01-15
