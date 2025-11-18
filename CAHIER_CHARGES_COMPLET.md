# CAHIER DES CHARGES COMPLET
## Plateforme de Marketing Digital pour Restaurants - Type Malou.io

**Version:** 1.0  
**Date:** 18 Novembre 2025  
**Document:** Récapitulatif Complet - Toutes Parties

---

## STRUCTURE DOCUMENTAIRE

Ce cahier des charges est organisé en 3 parties complémentaires :

### ✅ PARTIE 1 - Modules Principaux (Disponible : cahier_charges_partie1.md)
Présentation, contexte et 3 premiers modules détaillés avec code, algorithmes et interfaces.

### 📋 PARTIE 2 - Modules Avancés (Ce document - Section I)
Messagerie, présence management et analytics avec spécifications techniques.

### 🔧 PARTIE 3 - Technique & Business (Ce document - Section II)  
IA, intégrations, architecture Laravel, sécurité, roadmap et budget.

---

# SECTION I - MODULES AVANCÉS (PARTIE 2)

## MODULE 4: MESSAGERIE UNIFIÉE

### Objectifs
Centraliser toutes les conversations clients (FB Messenger, Instagram DM, WhatsApp, Google, Email, SMS) dans une interface unique pour répondre rapidement (<30min) et efficacement (taux réponse >90%).

### Fonctionnalités Principales

#### 4.1 Agrégation Multi-Canaux
- **Canaux intégrés:** 12+ sources (réseaux sociaux, plateformes avis, site web, email, SMS)
- **Synchronisation:** Temps réel via webhooks + polling intelligent
- **Normalisation:** Format unifié pour tous les messages
- **Détection doublons:** Évite messages dupliqués inter-plateformes

#### 4.2 Chatbot Intelligent
- **NLU avancé:** Compréhension intentions et entités
- **Réponses automatiques:** FAQ, horaires, menu, réservations
- **Contexte conversationnel:** Mémorise le fil de discussion
- **Escalade intelligente:** Transfert vers humain si confiance <70%
- **Multilingue:** Détection auto + réponse dans langue client

#### 4.3 Gestion d'Équipe
- **Assignation automatique:** Round-robin, charge travail, compétences, langue
- **Collaboration:** Notes internes, mentions @, transferts
- **SLA:** Temps réponse trackés avec alertes
- **Statuts conversations:** Nouveau, en cours, en attente, résolu, fermé

#### 4.4 Workflows Automatisés
- Confirmation réservation automatique
- Rappels J-1
- Demande d'avis J+2
- Relances panier abandonné
- Anniversaire client

### Données Clés
- **Temps première réponse (FRT):** Médiane <30min
- **Score Performance Messagerie:** 0-100 (réactivité 40%, taux réponse 30%, résolution 20%, satisfaction 10%)
- **Capacité agent:** Max 10-15 conversations simultanées

---

## MODULE 5: PRÉSENCE MANAGEMENT

### Objectifs
Gérer depuis une interface unique toutes les informations du restaurant sur 30+ plateformes, garantir cohérence NAP à 100%, et économiser 15h/mois.

### Fonctionnalités Principales

#### 5.1 Profil Unique Multi-Plateformes
- **Edition centralisée:** Un formulaire pour toutes les plateformes
- **Publication 1-clic:** Mise à jour simultanée sur toutes plateformes
- **Adaptation automatique:** Formats et limites spécifiques par plateforme
- **Historique:** 100 dernières modifications + rollback

**Plateformes gérées:**
- Google Business, Facebook, Instagram, Apple Maps, Bing
- TripAdvisor, Yelp, TheFork, OpenTable, Zomato
- PagesJaunes, Foursquare, Deliveroo, Uber Eats
- + 15 autres annuaires

#### 5.2 Détection Incohérences NAP
- **Scan quotidien:** Vérification Name, Address, Phone sur toutes plateformes
- **Algorithme normalisation:** Tolère variations mineures
- **Score cohérence:** 0-100% avec détail des problèmes
- **Correction auto:** Mise à jour en masse via APIs

#### 5.3 Gestion des Menus
- **Éditeur digital:** Structure catégories > sous-catégories > plats
- **Métadonnées complètes:** Prix, allergènes, labels (vegan, sans gluten, etc.)
- **Formats générés:**
  - PDF design (impression/affichage)
  - Menu web responsive
  - QR Code menu digital
  - Google Food Menu (structured data)
  - Formats plateformes livraison

#### 5.4 Bibliothèque Photos
- **Upload & traitement:** Génération thumbnails, analyse IA
- **IA Vision:** Auto-tagging, catégorisation, score qualité
- **Gestion avancée:** Tags, usage tracking, performance
- **Stockage:** 10GB inclus, CDN pour chargement rapide

#### 5.5 Multi-Établissements
- **Dashboard groupe:** Vue consolidée tous établissements
- **Templates:** Réutilisation configs entre restaurants
- **Actions groupées:** Modifications en masse
- **Cohérence marque:** Charte graphique centralisée

### Score de Présence
```
Score = (Complétude×0.25) + (Cohérence×0.25) + (Fraîcheur×0.15) + 
        (Qualité_Visuels×0.20) + (Engagement×0.15)
```

---

## MODULE 6: ANALYTICS & REPORTING

### Objectifs
Vue 360° de la performance marketing, mesure claire du ROI, insights actionnables, économie 90% temps reporting (45min vs 8h).

### Fonctionnalités Principales

#### 6.1 Dashboard Global
- **Score Performance:** 0-100 agrégé tous modules
- **KPIs principaux:** Visibilité, engagement, réputation, conversions, ROI
- **Graphiques évolution:** 30/90/365 jours
- **Comparaisons:** Période précédente, année dernière
- **Top Insights:** 3-5 insights actionnables prioritaires

#### 6.2 Analytics SEO Local
**Google Business Profile:**
- Vues profil (Search vs Maps)
- Actions (appels, itinéraires, clics site, réservations)
- Type recherches (directes vs découverte)
- Performance photos

**Mots-Clés:**
- Position par mot-clé (évolution historique)
- Impressions, clics, CTR
- Présence Local Pack (top 3)
- Benchmark concurrents

#### 6.3 Analytics Réseaux Sociaux
**Métriques par plateforme:**
- Croissance followers
- Reach & impressions
- Taux d'engagement (global + par post)
- Profil visits, website clicks

**Content Performance:**
- Top posts / worst posts
- Meilleurs horaires publication
- Types contenu performants (photo/vidéo/carousel)
- Hashtags efficaces

**Audience Insights:**
- Démographie (âge, sexe, localisation)
- Heures de connexion
- Intérêts

#### 6.4 ROI Marketing
**Calcul automatique:**
```
Coûts = Abonnement + Publicité + Temps_Équipe + Création_Contenu
Revenus = Nouveaux_Clients + Réservations_Trackées + Commandes_Ligne + Impact_Indirect
ROI = ((Revenus - Coûts) / Coûts) × 100
```

**Attribution multi-touch:**
- Premier contact, dernier contact, linéaire, time decay
- Parcours client complet
- Touchpoints optimaux

#### 6.5 Rapports Personnalisés
**Types:**
- Exécutif (2-3 pages, KPIs + ROI)
- Opérationnel (10-15 pages, détails canaux)
- Financier (focus coûts/revenus)
- Comparatif (multi-établissements)

**Formats:** PDF, Excel, PowerPoint
**Automatisation:** Envoi hebdo/mensuel programmé

---

# SECTION II - TECHNIQUE & BUSINESS (PARTIE 3)

## MODULE 7: INTELLIGENCE ARTIFICIELLE

### Vision IA
L'IA est au cœur de la plateforme pour automatiser les tâches répétitives, optimiser les performances et générer des insights actionnables.

### 7.1 Génération de Contenu

#### Posts Réseaux Sociaux
- **Input:** Type (promo/événement/plat), thème, ton, longueur
- **Output:** 3 variantes de texte optimisées
- **Modèle:** GPT-4 fine-tuné sur contenu restaurants
- **Personnalisation:** Apprentissage style marque
- **Temps génération:** <5 secondes

#### Réponses aux Avis
- **Analyse sentiment:** Score -1 à +1
- **Extraction thèmes:** Nourriture, service, ambiance, prix
- **Génération réponse:** Personnalisée, ton adapté
- **Templates dynamiques:** Variables {nom_client}, {plat_mentionné}

#### Descriptions SEO
- Meta descriptions optimisées (150-160 car)
- Descriptions GMB (750 car)
- Alt text images
- Intégration naturelle mots-clés

### 7.2 Analyse et Insights

#### Analyse Sentiment Avancée
- **NLP:** BERT ou modèle équivalent
- **Détection émotions:** Joie, colère, déception, surprise
- **Aspects:** Extraction automatique (nourriture+, service-, etc.)
- **Sarcasme/Ironie:** Détection contexte

#### Extraction d'Insights Clients
- Thèmes récurrents avis (positifs/négatifs)
- Plats les plus/moins appréciés
- Opportunités d'amélioration
- Prédiction satisfaction client

#### Détection d'Anomalies
- Chute engagement >20%
- Pic avis négatifs
- Pattern inhabituel messages
- Fake reviews

### 7.3 Optimisation Automatique

#### Horaires Publication
- Analyse historique engagement
- Activité audience par heure/jour
- Recommandations top 3 créneaux/plateforme

#### Suggestions Contenu
- Tendances secteur
- Saisonnalité
- Événements locaux
- Contenus performants concurrents

#### Budget Publicitaire
- Allocation optimale inter-plateformes
- Audiences prioritaires
- Créatives à booster
- ROI prédictif

### 7.4 Personnalisation

#### Segmentation Clients
- Nouveaux, réguliers, VIP, à risque, perdus
- Critères: fréquence, montant, ancienneté, engagement
- Actions automatisées par segment

#### Messages Personnalisés
- Nom, références visites passées
- Suggestions basées préférences
- Offres ciblées par profil

### Stack IA
- **LLM:** GPT-4, Claude 3 (génération texte)
- **Vision:** GPT-4V, Claude Vision (analyse images)
- **NLP:** BERT, spaCy (analyse sentiment)
- **ML:** scikit-learn, TensorFlow (prédictions)

---

## MODULE 8: INTÉGRATIONS

### 8.1 Intégrations Natives

#### Réseaux Sociaux
- **Facebook:** Pages API, Messenger API, Ads API
- **Instagram:** Graph API, Direct API
- **TikTok:** Business API
- **LinkedIn:** Marketing API
- **Twitter/X:** API v2

#### Plateformes Avis
- **Google:** My Business API v4.9
- **TripAdvisor:** Content API
- **Yelp:** Fusion API
- **Trustpilot:** API

#### Réservation & Livraison
- **TheFork/LaFourchette:** Partner API
- **OpenTable:** Guest Center API
- **Uber Eats:** Restaurant Dashboard API
- **Deliveroo:** Partner API
- **Just Eat:** Integration API

### 8.2 Webhooks
- **Temps réel:** Nouveaux messages, avis, commentaires
- **Retry automatique:** 3 tentatives avec backoff exponentiel
- **Signature validation:** HMAC-SHA256
- **Rate limiting:** 100 req/min

### 8.3 API Publique
- **REST:** JSON, OAuth 2.0
- **Endpoints principaux:**
  - `/businesses` - Gestion profils
  - `/posts` - Publications
  - `/reviews` - Avis
  - `/messages` - Conversations
  - `/analytics` - Métriques
- **Rate limit:** 1000 req/heure
- **Documentation:** OpenAPI 3.0 (Swagger)

### 8.4 Intégrations Business

#### CRM
- Salesforce, HubSpot, Pipedrive
- Sync contacts bidirectionnel
- Enrichissement données clients

#### PMS (Property Management System)
- Pour hôtels-restaurants
- Sync réservations, clients

#### Outils Marketing
- Mailchimp, Sendinblue (email)
- Twilio (SMS)
- Google Analytics, Facebook Pixel

---

## MODULE 9: ADMINISTRATION

### 9.1 Gestion Utilisateurs

#### Rôles et Permissions
```
Super Admin (Groupe)
├── Admin Restaurant
│   ├── Manager
│   │   ├── Agent Marketing
│   │   └── Agent Support
│   └── Viewer (Lecture seule)
└── Comptable (Accès financier uniquement)
```

**Permissions granulaires:**
- Gestion profil business
- Publication contenu
- Réponse messages/avis
- Gestion équipe
- Accès analytics
- Facturation

### 9.2 Facturation

#### Plans Tarifaires
**Starter:** 149€/mois
- 1 établissement
- Modules de base
- 10GB stockage
- Support email

**Professional:** 299€/mois  
- 3 établissements
- Tous modules
- 50GB stockage
- Support prioritaire
- Rapports avancés

**Enterprise:** Sur devis
- Illimité établissements
- White-label possible
- Stockage illimité
- Support dédié
- SLA garanti
- Développements custom

#### Paiement
- Mensuel ou annuel (-20%)
- Carte bancaire, virement, prélèvement
- Facturation automatique
- Historique factures

### 9.3 Support

#### Canaux
- **Base de connaissances:** 200+ articles
- **Chat live:** Heures bureau (9h-18h)
- **Email:** support@restaurantboost.com (<24h)
- **Téléphone:** Plans Pro+ uniquement
- **Webinaires:** Formation mensuelle

#### SLA Support
- **Starter:** 48h (email)
- **Professional:** 24h (email), 4h (chat)
- **Enterprise:** 12h (email), 2h (chat), 1h (phone)

### 9.4 Paramètres Système

#### Configuration Globale
- Langue interface (FR/EN/ES/IT/AR)
- Timezone
- Devise
- Format date/heure
- Notifications (email/SMS/push)

#### Intégrations
- Activation/désactivation par module
- Clés API tierces
- Webhooks personnalisés

#### Sécurité
- MFA obligatoire (admins)
- IP whitelisting
- Sessions timeout (30min inactivité)
- Logs d'audit

---

## ARCHITECTURE TECHNIQUE

### Stack Technologique

#### Backend
- **Framework:** Laravel 10 (PHP 8.2)
- **Architecture:** MVC + Repository Pattern
- **API:** RESTful + GraphQL (optionnel)
- **Queue:** Laravel Horizon (Redis)
- **Jobs:** Traitement asynchrone
- **Cache:** Redis
- **Search:** Algolia ou MeiliSearch

#### Frontend
- **Framework:** Vue.js 3 + TypeScript
- **State Management:** Pinia
- **UI Components:** Tailwind CSS + Headless UI
- **Charts:** Chart.js ou ApexCharts
- **Real-time:** Laravel Echo + Pusher

#### Mobile
- **Framework:** Flutter (iOS + Android)
- **Architecture:** BLoC pattern
- **API:** REST Laravel

#### Infrastructure
- **Hosting:** AWS ou GCP
- **CDN:** CloudFront ou Cloudflare
- **Database:** PostgreSQL 15 (primary), MySQL (compat)
- **Storage:** S3 (photos/médias)
- **Monitoring:** Sentry, New Relic
- **CI/CD:** GitHub Actions ou GitLab CI

### Base de Données

#### Schéma Principal (PostgreSQL)

```sql
-- Businesses
CREATE TABLE businesses (
    id UUID PRIMARY KEY,
    group_id UUID REFERENCES business_groups(id),
    name VARCHAR(255) NOT NULL,
    legal_name VARCHAR(255),
    description TEXT,
    address JSONB,
    phone VARCHAR(50),
    email VARCHAR(255),
    website VARCHAR(255),
    metadata JSONB,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- Users
CREATE TABLE users (
    id UUID PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255),
    role ENUM('super_admin', 'admin', 'manager', 'agent', 'viewer'),
    mfa_enabled BOOLEAN DEFAULT FALSE,
    last_login_at TIMESTAMP,
    created_at TIMESTAMP
);

-- Business Users (pivot)
CREATE TABLE business_users (
    business_id UUID REFERENCES businesses(id),
    user_id UUID REFERENCES users(id),
    role VARCHAR(50),
    permissions JSONB,
    PRIMARY KEY (business_id, user_id)
);

-- Reviews
CREATE TABLE reviews (
    id UUID PRIMARY KEY,
    business_id UUID REFERENCES businesses(id),
    platform VARCHAR(50),
    platform_review_id VARCHAR(255),
    author_name VARCHAR(255),
    rating DECIMAL(2,1),
    text TEXT,
    created_at TIMESTAMP,
    response_text TEXT,
    response_date TIMESTAMP,
    sentiment_score DECIMAL(3,2),
    categories JSONB,
    INDEX idx_business_platform (business_id, platform),
    INDEX idx_rating (rating),
    INDEX idx_created_at (created_at)
);

-- Social Posts
CREATE TABLE social_posts (
    id UUID PRIMARY KEY,
    business_id UUID REFERENCES businesses(id),
    platforms JSONB, -- ['facebook', 'instagram']
    content TEXT,
    media_urls JSONB,
    scheduled_at TIMESTAMP,
    published_at TIMESTAMP,
    status ENUM('draft', 'scheduled', 'publishing', 'published', 'failed'),
    metrics JSONB, -- likes, comments, shares, etc.
    created_by UUID REFERENCES users(id),
    created_at TIMESTAMP
);

-- Messages
CREATE TABLE messages (
    id UUID PRIMARY KEY,
    conversation_id UUID REFERENCES conversations(id),
    direction ENUM('inbound', 'outbound'),
    sender_type ENUM('customer', 'agent', 'bot'),
    sender_id VARCHAR(255),
    content JSONB,
    timestamp TIMESTAMP,
    read_at TIMESTAMP,
    INDEX idx_conversation (conversation_id),
    INDEX idx_timestamp (timestamp)
);

-- Conversations
CREATE TABLE conversations (
    id UUID PRIMARY KEY,
    business_id UUID REFERENCES businesses(id),
    channel ENUM('facebook', 'instagram', 'whatsapp', 'google', 'email', 'sms'),
    customer_id VARCHAR(255),
    customer_name VARCHAR(255),
    assigned_to UUID REFERENCES users(id),
    status ENUM('new', 'open', 'pending', 'resolved', 'closed'),
    tags JSONB,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    resolved_at TIMESTAMP
);

-- Analytics
CREATE TABLE analytics_daily (
    id UUID PRIMARY KEY,
    business_id UUID REFERENCES businesses(id),
    date DATE NOT NULL,
    module VARCHAR(50), -- 'seo', 'social', 'reviews', etc.
    metrics JSONB,
    UNIQUE(business_id, date, module),
    INDEX idx_business_date (business_id, date)
);
```

### Performance & Scalabilité

#### Optimisations
- **Caching:** Redis (sessions, configs, queries fréquentes)
- **CDN:** Images/médias via CloudFront
- **Database:**
  - Index sur colonnes recherchées
  - Partitioning par date (analytics)
  - Read replicas pour analytics
- **Queue:** Jobs asynchrones (emails, sync APIs, rapports)
- **Lazy Loading:** Pagination 50 items/page

#### Scalabilité
- **Horizontal:** Load balancer + multi-instances app
- **Vertical:** Upgrade database selon charge
- **Cache distribué:** Redis Cluster
- **Queue workers:** Auto-scaling selon charge

#### Capacité Cible
- **Utilisateurs:** 10,000+ comptes simultanés
- **Restaurants:** 5,000+ établissements
- **Messages/jour:** 100,000+
- **API calls/jour:** 1M+
- **Storage photos:** 1TB+

---

## SÉCURITÉ ET CONFORMITÉ

### 10.1 Sécurité Application

#### Authentification
- **MFA:** TOTP (Google Authenticator, Authy)
- **OAuth 2.0:** Login social (Google, Facebook)
- **Session:** Expiration 30min inactivité
- **Password policy:** Min 12 caractères, majuscule, chiffre, symbole
- **Brute force:** Rate limiting 5 tentatives/IP

#### Autorisation
- **RBAC:** Role-Based Access Control
- **Permissions granulaires:** Par module/action
- **API tokens:** Scoped, révocables
- **Audit logs:** Toutes actions sensibles loggées

#### Data Protection
- **Encryption at rest:** AES-256 (database, backups)
- **Encryption in transit:** TLS 1.3
- **Secrets management:** AWS Secrets Manager ou Vault
- **PII masking:** Dans logs et interfaces

#### Vulnerabilités
- **OWASP Top 10:** Protection complète
- **SQL Injection:** Prepared statements
- **XSS:** Content Security Policy, sanitization
- **CSRF:** Tokens Laravel
- **Dependency scanning:** Snyk ou Dependabot
- **Penetration testing:** Annuel par tierce partie

### 10.2 Conformité RGPD

#### Principes
- **Minimisation:** Collecte données strictement nécessaires
- **Transparence:** Privacy policy claire
- **Consentement:** Opt-in explicite (newsletters, cookies)
- **Portabilité:** Export données format structuré
- **Droit à l'oubli:** Suppression complète sous 30 jours

#### Mise en Œuvre
- **DPO:** Data Protection Officer désigné
- **Privacy by Design:** RGPD dès conception
- **DPIA:** Data Protection Impact Assessment
- **Registre traitements:** Conforme article 30
- **DPA:** Data Processing Agreements avec sous-traitants

#### Données Personnelles
**Collectées:**
- Identité: nom, prénom, email, téléphone
- Localisation: adresse établissement
- Professionnelles: rôle, entreprise
- Techniques: IP, cookies, logs

**Conservation:**
- Comptes actifs: durée contrat + 3 ans
- Logs: 12 mois
- Analytics: 3 ans anonymisées
- Backups: 90 jours

**Transferts hors UE:**
- USA: clauses contractuelles types (SCC)
- Hébergement prioritaire UE

### 10.3 Certifications

#### Cibles
- **ISO 27001:** Sécurité information
- **SOC 2 Type II:** Controls audit
- **GDPR Compliant:** Conformité RGPD
- **PCI-DSS:** Si paiement CB intégré

---

## ROADMAP DE DÉVELOPPEMENT

### Phase 1 - MVP (6 mois)

**Mois 1-2: Foundation**
- Setup infrastructure (AWS, database, CI/CD)
- Architecture Laravel + Vue.js
- Authentification & gestion utilisateurs
- Dashboard principal

**Mois 3-4: Core Modules**
- Module SEO (Google Business Profile)
- Module E-Réputation (agrégation avis)
- Module Présence (profil unique)
- Intégrations APIs principales

**Mois 5-6: Advanced Features**
- Module Community Management (posts sociaux)
- Module Messagerie (basique)
- Analytics dashboards
- Tests & debugging

**Livrable:** Plateforme fonctionnelle avec 4 modules essentiels

### Phase 2 - Growth (6 mois)

**Mois 7-9: IA & Automation**
- Chatbot intelligent
- Génération contenu IA
- Réponses automatiques avis
- Workflows automatisés

**Mois 10-12: Scale & Polish**
- Application mobile (Flutter)
- Multi-établissements avancé
- Rapports personnalisés
- Performance optimization

**Livrable:** Plateforme complète avec IA et mobile

### Phase 3 - Enterprise (6 mois)

**Mois 13-15: Enterprise Features**
- White-label
- API publique avancée
- Intégrations CRM/PMS
- Advanced analytics

**Mois 16-18: International & Certification**
- Support multilingue complet
- Conformité certifications (ISO, SOC2)
- Expansion géographique
- Marketplace intégrations

**Livrable:** Solution enterprise-ready

---

## BUDGET ET RESSOURCES

### 11.1 Équipe de Développement

#### Phase 1 - MVP (6 mois)
**Team:**
- 1 Tech Lead / Architecte (full-time)
- 2 Développeurs Backend Laravel (full-time)
- 2 Développeurs Frontend Vue.js (full-time)
- 1 DevOps Engineer (full-time)
- 1 UI/UX Designer (mi-temps)
- 1 QA Engineer (full-time à partir M4)
- 1 Product Owner (full-time)

**Total:** 7.5 ETP

#### Phase 2 - Growth (6 mois)
**Team** (ajouts):
- +1 Développeur Backend (IA/ML)
- +1 Développeur Mobile Flutter
- +1 Data Scientist

**Total:** 10.5 ETP

#### Phase 3 - Enterprise (6 mois)
**Team** (ajouts):
- +1 Architecte Solutions
- +1 Security Engineer
- +1 Technical Writer

**Total:** 13.5 ETP

### 11.2 Coûts de Développement

#### Phase 1 - MVP (6 mois)
- **Salaires équipe:** 375,000€ (7.5 ETP × 10K€/mois/ETP × 5)
- **Infrastructure AWS:** 6,000€ (1K€/mois × 6)
- **Outils & Licences:** 5,000€ (Figma, JetBrains, monitoring)
- **APIs tierces:** 3,000€ (quotas développement)
- **Marketing/recrutement:** 10,000€
**Total Phase 1:** 399,000€

#### Phase 2 - Growth (6 mois)
- **Salaires équipe:** 630,000€ (10.5 ETP × 10K€/mois × 6)
- **Infrastructure:** 12,000€ (2K€/mois × 6, scaling)
- **APIs & services:** 6,000€
- **Marketing (early adopters):** 30,000€
**Total Phase 2:** 678,000€

#### Phase 3 - Enterprise (6 mois)
- **Salaires équipe:** 810,000€ (13.5 ETP × 10K€/mois × 6)
- **Infrastructure:** 18,000€ (3K€/mois × 6)
- **Certifications:** 50,000€ (ISO 27001, SOC2, audits)
- **Marketing & Sales:** 100,000€
**Total Phase 3:** 978,000€

**TOTAL 18 MOIS:** 2,055,000€

### 11.3 Coûts Opérationnels Récurrents (post-lancement)

#### Mensuel
- **Infrastructure AWS:** 5,000€ (100 clients)
- **APIs tierces:** 2,000€ (quotas production)
- **Monitoring & alerting:** 500€
- **Support & maintenance:** 15,000€ (1.5 ETP)
- **Sales & marketing:** 10,000€
**Total/mois:** 32,500€

#### Annuel
**Total/an:** 390,000€

### 11.4 Business Model

#### Revenus Prévisionnels

**Année 1** (Mois 13-24 post-dev)
- Clients: 100 → 500 (croissance progressive)
- MRR moyen: 200€ (mix Starter/Pro)
- ARR fin année: 1,200,000€

**Année 2**
- Clients: 500 → 1,200
- MRR moyen: 220€ (plus de Pro)
- ARR fin année: 3,168,000€

**Année 3**
- Clients: 1,200 → 2,000
- MRR moyen: 250€ (Enterprise)
- ARR fin année: 6,000,000€

#### Rentabilité
- **Break-even:** Mois 30 (2.5 ans)
- **ROI 5 ans:** 180%

### 11.5 Financement

#### Option 1: Bootstrapping
- Investissement initial: 400K€ (Phase 1)
- Autofinancement Phase 2-3 via early adopters

#### Option 2: Levée de Fonds
- **Seed Round:** 1.5M€ (pré-lancement)
  - Couvre 18 mois développement
  - 12 mois opérationnel post-lancement
  - Dilution: 15-20%

- **Series A:** 5M€ (après product-market fit)
  - Scale équipes (sales, support, dev)
  - Expansion internationale
  - Dilution: 20-25%

---

## CONCLUSION

### Résumé Exécutif

**RestauBoost** est une plateforme SaaS complète de marketing digital pour restaurants, inspirée de Malou.io, offrant:

✅ **9 modules intégrés** couvrant tous les besoins marketing
✅ **Intelligence Artificielle** au cœur du produit
✅ **30+ intégrations** natives (Google, Facebook, Instagram, TripAdvisor, etc.)
✅ **ROI mesurable** : +320% en moyenne
✅ **Gain de temps** : 28h/mois économisées
✅ **Results** : +18% clients, +350% visibilité, note +0.5★

### Différenciateurs Clés

1. **Tout-en-un:** Un seul outil vs 10+ outils séparés
2. **IA Native:** Génération contenu, chatbot, insights automatiques
3. **Multi-établissements:** Gestion centralisée groupes/chaînes
4. **ROI Transparent:** Mesure claire du retour sur investissement
5. **Support Expert:** Équipe spécialisée secteur restauration

### Prochaines Étapes

1. **Validation Concept:** Interviews 50 restaurateurs cibles
2. **Finalisation Specs:** Ajustements selon feedbacks
3. **Constitution Équipe:** Recrutement Tech Lead + Devs
4. **Lancement Développement:** Démarrage Phase 1 MVP
5. **Early Adopters:** Programme bêta fermée (20 restaurants)

### Contact

**Projet:** RestauBoost - Plateforme Marketing Restaurants  
**Date:** 18 Novembre 2025  
**Version:** 1.0 - Cahier des charges complet

---

## ANNEXES

### A. Glossaire

- **ARR:** Annual Recurring Revenue (revenu récurrent annuel)
- **CAC:** Customer Acquisition Cost (coût d'acquisition client)
- **CSAT:** Customer Satisfaction Score
- **ETP:** Équivalent Temps Plein
- **FRT:** First Response Time (temps première réponse)
- **GMB:** Google My Business
- **KPI:** Key Performance Indicator
- **LTV:** Lifetime Value (valeur vie client)
- **MFA:** Multi-Factor Authentication
- **MRR:** Monthly Recurring Revenue
- **NAP:** Name, Address, Phone
- **NLP:** Natural Language Processing
- **NPS:** Net Promoter Score
- **RGPD:** Règlement Général sur la Protection des Données
- **ROI:** Return On Investment
- **SaaS:** Software as a Service
- **SEO:** Search Engine Optimization
- **SLA:** Service Level Agreement
- **TTR:** Time To Resolution

### B. Bibliographie

- Google My Business API Documentation
- Facebook Graph API Documentation
- Instagram Business API Documentation
- RGPD - Texte officiel
- OWASP Top 10 Security Risks
- Laravel Best Practices
- Restaurant Industry Reports 2024-2025

### C. Contacts Équipe

- **Product Owner:** [À définir]
- **Tech Lead:** [À définir]
- **UI/UX Lead:** [À définir]
- **Business Development:** [À définir]

---

**FIN DU CAHIER DES CHARGES**

*Document technique et confidentiel - Tous droits réservés*
