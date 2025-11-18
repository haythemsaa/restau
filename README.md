# RestauBoost - Plateforme Marketing Digital pour Restaurants 🍽️

![Laravel](https://img.shields.io/badge/Laravel-10.x-red.svg)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-15-blue.svg)
![React Native](https://img.shields.io/badge/React_Native-0.73-blue.svg)
![Vue.js](https://img.shields.io/badge/Vue.js-3.x-green.svg)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-purple.svg)

**RestauBoost** est une plateforme SaaS complète de marketing digital pour restaurants. Solution tout-en-un pour gérer la relation client, l'e-réputation, les campagnes marketing et l'intelligence artificielle.

---

## 🌟 Fonctionnalités Principales

### ✨ Intelligence Artificielle
- **Génération de Contenu** - Création automatique de posts pour Facebook, Instagram, Twitter, Email
- **Analyse de Sentiment** - Analyse multi-dimensionnelle des avis clients
- **Réponses Automatiques** - Génération de réponses personnalisées aux avis
- **Tons Personnalisables** - Professionnel, Amical, Enthousiaste, Décontracté

### 👥 CRM & Gestion Clients
- **Profils Clients Complets** - Informations, préférences, allergies, historique
- **Segmentation Avancée** - Filtres multiples et segments personnalisés
- **Score RFM** - Analyse Récence, Fréquence, Montant pour chaque client
- **Tiers VIP** - Classification automatique (Regular, VIP, Super VIP)
- **Détection Clients à Risque** - Identification proactive des clients inactifs
- **Historique des Visites** - Tracking complet des interactions

### 📧 Email Marketing
- **Campagnes Ciblées** - Envoi massif avec segmentation
- **Templates Professionnels** - 5+ templates HTML prêts à l'emploi
  - Bienvenue nouveau client
  - Réactivation clients inactifs
  - Programme VIP
  - Nouveau menu saisonnier
  - Anniversaire client
- **Variables Dynamiques** - Personnalisation automatique
- **Statistiques Détaillées** - Taux d'ouverture, clics, bounces
- **Planification** - Envoi immédiat ou programmé

### 📊 Analytics & Reporting
- **Dashboard Interactif** - Statistiques en temps réel
- **Graphiques Avancés** - Chart.js pour visualisation des données
- **KPIs Personnalisés** - Métriques adaptées aux restaurants
- **Export de Données** - Rapports PDF et Excel

### 📱 Application Mobile React Native
- **Cross-Platform** - iOS et Android natifs
- **Design Moderne** - UI élégante avec gradients et animations
- **Synchronisation Temps Réel** - Données toujours à jour
- **Mode Hors-Ligne** - Persistance locale avec AsyncStorage
- **Navigation Fluide** - React Navigation avec tabs et stacks
- Écrans disponibles :
  - Dashboard avec stats et graphiques
  - Liste et détail clients
  - Générateur IA mobile
  - Authentification complète

### 🎨 Interface Web Moderne
- **Bootstrap 5.3** - Design responsive et professionnel
- **Animations AOS** - Scroll animations élégantes
- **Sidebar Moderne** - Navigation fluide avec gradients
- **Cartes Interactives** - Effets hover et transitions CSS
- **JavaScript Vanilla** - Interactions sans dépendances lourdes
- **Charts Interactifs** - Visualisations de données

---

## 📂 Structure du Projet

```
restauboost/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── API/V1/
│   │   │   │   ├── AIController.php
│   │   │   │   ├── CustomerController.php
│   │   │   │   ├── EmailCampaignController.php
│   │   │   │   └── ...
│   │   ├── Requests/
│   │   └── Resources/
│   ├── Models/
│   │   ├── Customer.php
│   │   ├── CustomerVisit.php
│   │   ├── CustomerSegment.php
│   │   ├── EmailCampaign.php
│   │   ├── EmailTemplate.php
│   │   └── ...
│   ├── Services/
│   │   ├── AI/
│   │   │   ├── ContentGeneratorService.php
│   │   │   ├── SentimentAnalysisService.php
│   │   │   └── ReviewResponseService.php
│   │   └── RFMAnalysisService.php
│   └── Jobs/
│       └── SendCampaignEmail.php
│
├── database/
│   ├── migrations/
│   ├── seeders/
│   │   ├── CustomerSeeder.php
│   │   ├── EmailTemplateSeeder.php
│   │   ├── EmailCampaignSeeder.php
│   │   └── DatabaseSeeder.php
│   └── factories/
│
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   └── app.blade.php
│   │   ├── dashboard/
│   │   │   └── index.blade.php
│   │   ├── customers/
│   │   │   └── index.blade.php
│   │   └── ai/
│   │       └── content-generator.blade.php
│   └── js/
│
├── public/
│   ├── css/
│   │   └── app.css (600+ lignes)
│   └── js/
│       └── app.js (300+ lignes)
│
├── mobile/                    # Application React Native
│   ├── src/
│   │   ├── screens/          # Écrans (8+)
│   │   ├── components/       # Composants réutilisables (6)
│   │   ├── navigation/       # Configuration navigation
│   │   ├── services/         # API service
│   │   ├── context/          # State management
│   │   └── utils/            # Theme, animations
│   ├── App.js
│   ├── package.json
│   └── README.md
│
├── tests/
│   ├── Feature/
│   │   ├── AIControllerTest.php
│   │   └── CustomerControllerTest.php
│   └── Unit/
│       ├── ContentGeneratorServiceTest.php
│       ├── SentimentAnalysisServiceTest.php
│       └── CustomerTest.php
│
├── scripts/
│   ├── setup.sh             # Installation automatique
│   ├── deploy.sh            # Déploiement production
│   └── backup.sh            # Sauvegarde complète
│
├── API_DOCUMENTATION.md      # Documentation API complète
├── OPTIMIZATION.md           # Guide d'optimisation
├── .env.example             # Configuration exemple
└── README.md                # Ce fichier
```

---

## 🛠 Stack Technique

### Backend
- **Laravel 10.x** - Framework PHP moderne
- **PHP 8.2+** - Langage backend
- **PostgreSQL 15** - Base de données relationnelle
- **Redis** - Cache et queues
- **Laravel Sanctum** - Authentification API
- **Laravel Horizon** - Supervision des queues
- **OpenAI GPT-4** - Intelligence artificielle

### Frontend Web
- **Bootstrap 5.3.2** - Framework CSS
- **Vanilla JavaScript ES6+** - Interactions
- **AOS 2.3.1** - Animations scroll
- **Chart.js 4.4.0** - Graphiques
- **Blade Templates** - Moteur de templates Laravel

### Mobile
- **React Native 0.73.2** - Framework mobile
- **React Navigation v6** - Navigation
- **AsyncStorage** - Stockage local
- **Axios** - Requêtes HTTP
- **React Native Linear Gradient** - Effets visuels
- **React Native Chart Kit** - Graphiques mobiles
- **React Native Vector Icons** - Icônes

### DevOps & Tools
- **Git** - Contrôle de version
- **Composer** - Gestionnaire de dépendances PHP
- **NPM** - Gestionnaire de dépendances JavaScript
- **PHPUnit** - Tests unitaires
- **GitHub Actions** - CI/CD (à venir)

---

## 📦 Installation

### Installation Automatique (Recommandé)

```bash
# Cloner le repository
git clone https://github.com/haythemsaa/restau.git
cd restau

# Lancer le script d'installation
chmod +x scripts/setup.sh
./scripts/setup.sh
```

Le script configure automatiquement:
- ✓ Fichier .env
- ✓ Application key
- ✓ Dépendances Composer et NPM
- ✓ Base de données PostgreSQL
- ✓ Migrations
- ✓ Seeders (données de démo)
- ✓ Storage link
- ✓ Assets frontend

### Installation Manuelle

<details>
<summary>Cliquez pour voir les étapes détaillées</summary>

#### Prérequis
- PHP 8.2+
- Composer 2.x
- PostgreSQL 15
- Redis 6+
- Node.js 18+
- NPM 9+

#### Étapes

1. **Cloner le repository**
```bash
git clone https://github.com/haythemsaa/restau.git
cd restau
```

2. **Installer les dépendances PHP**
```bash
composer install
```

3. **Configuration de l'environnement**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configurer la base de données**

Modifier le fichier `.env`:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=restauboost
DB_USERNAME=postgres
DB_PASSWORD=votre_password
```

Créer la base de données:
```bash
createdb restauboost
```

5. **Configurer Redis**
```env
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
REDIS_CLIENT=phpredis
```

6. **Configurer OpenAI**
```env
OPENAI_API_KEY=your_openai_api_key_here
OPENAI_MODEL=gpt-4
```

7. **Exécuter les migrations**
```bash
php artisan migrate
```

8. **Seed la base de données**
```bash
php artisan db:seed
```

9. **Installer les dépendances Node.js**
```bash
npm install
```

10. **Compiler les assets**
```bash
npm run dev
# ou pour production
npm run build
```

11. **Créer le lien symbolique storage**
```bash
php artisan storage:link
```

12. **Démarrer le serveur**
```bash
php artisan serve
```

13. **Démarrer les workers (nouveau terminal)**
```bash
php artisan queue:work
```

</details>

---

## 🚀 Lancement

### Backend Laravel

```bash
# Serveur de développement
php artisan serve

# Workers pour les jobs
php artisan queue:work

# Ou avec Horizon (monitoring)
php artisan horizon
```

### Frontend Web

```bash
# Mode développement (avec hot reload)
npm run dev

# Build production
npm run build
```

### Application Mobile

```bash
cd mobile

# Installer les dépendances
npm install

# iOS (macOS uniquement)
cd ios && pod install && cd ..
npm run ios

# Android
npm run android

# Metro bundler (si besoin)
npm start
```

---

## 🔑 Données de Test

### Utilisateur de Démo
- **Email:** `demo@restauboost.com`
- **Mot de passe:** `password`

### Clients de Démonstration

Après le seed, vous aurez accès à:
- **50 clients** avec historique complet
- **15 clients VIP** actifs
- **8 clients** à risque
- **200+ visites** enregistrées
- **5 segments** pré-configurés
- **3 campagnes email** prêtes
- **5 templates email** professionnels

---

## 📖 Documentation

### Documentation Disponible

- **[API_DOCUMENTATION.md](./API_DOCUMENTATION.md)** - Documentation API complète
  - Tous les endpoints avec exemples
  - Authentification
  - Rate limiting
  - Codes d'erreur

- **[OPTIMIZATION.md](./OPTIMIZATION.md)** - Guide d'optimisation
  - Configuration Laravel
  - Optimisations base de données
  - Cache & Redis
  - Production checklist

- **[mobile/README.md](./mobile/README.md)** - Documentation mobile
  - Architecture React Native
  - Installation et configuration
  - Composants disponibles
  - API integration

### API Endpoints Principaux

#### Authentification
- `POST /api/v1/auth/register` - Inscription
- `POST /api/v1/auth/login` - Connexion
- `POST /api/v1/auth/logout` - Déconnexion
- `GET /api/v1/auth/me` - Profil utilisateur

#### Clients
- `GET /api/v1/customers` - Liste des clients
- `GET /api/v1/customers/{id}` - Détails client
- `POST /api/v1/customers` - Créer un client
- `PUT /api/v1/customers/{id}` - Mettre à jour
- `DELETE /api/v1/customers/{id}` - Supprimer

#### Intelligence Artificielle
- `POST /api/v1/ai/generate-content` - Générer du contenu
- `POST /api/v1/ai/analyze-sentiment` - Analyser sentiment
- `POST /api/v1/ai/generate-review-response` - Réponse automatique

#### Campagnes Email
- `GET /api/v1/email-campaigns` - Liste des campagnes
- `POST /api/v1/email-campaigns` - Créer une campagne
- `POST /api/v1/email-campaigns/{id}/send` - Envoyer
- `GET /api/v1/email-campaigns/{id}/stats` - Statistiques

Pour la documentation complète, voir [API_DOCUMENTATION.md](./API_DOCUMENTATION.md)

---

## 🧪 Tests

### Lancer les Tests

```bash
# Tous les tests
php artisan test

# Tests feature
php artisan test --testsuite=Feature

# Tests unitaires
php artisan test --testsuite=Unit

# Avec coverage
php artisan test --coverage
```

### Tests Disponibles

#### Feature Tests
- `AIControllerTest` - Tests endpoints IA
- `CustomerControllerTest` - Tests CRUD clients

#### Unit Tests
- `ContentGeneratorServiceTest` - Génération de contenu
- `SentimentAnalysisServiceTest` - Analyse sentiment
- `ReviewResponseServiceTest` - Réponses automatiques
- `CustomerTest` - Model Customer

---

## 🚢 Déploiement

### Déploiement Automatique

```bash
chmod +x scripts/deploy.sh
./scripts/deploy.sh production
```

Le script gère:
- Pull du code
- Installation des dépendances
- Migrations
- Clear & cache config
- Build assets
- Permissions
- Restart workers

### Checklist Manuelle Production

- [ ] Configurer `.env` production
- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] Configurer base de données
- [ ] Configurer Redis
- [ ] Configurer mail (SendGrid, SES, etc.)
- [ ] Configurer OpenAI API key
- [ ] Exécuter migrations
- [ ] Compiler assets (`npm run build`)
- [ ] Optimiser (`php artisan optimize`)
- [ ] Configurer supervisor pour queues
- [ ] Configurer nginx/apache
- [ ] Configurer SSL/TLS
- [ ] Configurer backups automatiques
- [ ] Configurer monitoring (Sentry, New Relic)

Voir [OPTIMIZATION.md](./OPTIMIZATION.md) pour les détails.

---

## 💾 Sauvegarde

### Backup Automatique

```bash
chmod +x scripts/backup.sh
./scripts/backup.sh
```

Crée:
- Dump PostgreSQL
- Archive des fichiers
- Compression complète
- Nettoyage backups > 30 jours

### Backup Manuel

```bash
# Base de données
pg_dump -U postgres restauboost > backup_$(date +%Y%m%d).sql

# Fichiers
tar -czf backup_files_$(date +%Y%m%d).tar.gz \
  --exclude='node_modules' \
  --exclude='vendor' \
  .
```

---

## 🔐 Sécurité

### Mesures Implémentées

- ✅ **Laravel Sanctum** - Authentification API sécurisée
- ✅ **CSRF Protection** - Tokens anti-CSRF
- ✅ **SQL Injection** - Eloquent ORM + Prepared Statements
- ✅ **XSS Protection** - Blade escaping automatique
- ✅ **Rate Limiting** - Protection brute force (60 req/min)
- ✅ **Password Hashing** - Bcrypt
- ✅ **HTTPS** - Recommandé en production
- ✅ **Input Validation** - Form Requests
- ✅ **Soft Deletes** - Récupération des données

### Configuration Recommandée

```env
# Production
APP_ENV=production
APP_DEBUG=false
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=strict

# API Rate Limiting
API_RATE_LIMIT=60
API_RATE_LIMIT_WINDOW=1
```

---

## 📊 Performance

### Métriques Cibles

- ⚡ **Temps de réponse API:** < 200ms
- ⚡ **Chargement page:** < 2 secondes
- ⚡ **TTFB:** < 500ms
- ⚡ **Cache hit ratio:** > 80%
- ⚡ **Traitement queue:** < 5min pour batch jobs

### Optimisations Implémentées

- Redis pour cache et sessions
- Eager loading des relations
- Index database optimisés
- Assets minifiés et compressés
- OPcache activé en production
- Queue workers pour tâches lourdes

Voir [OPTIMIZATION.md](./OPTIMIZATION.md) pour détails complets.

---

## 🤝 Contribution

Les contributions sont les bienvenues !

1. Fork le projet
2. Créer une branche (`git checkout -b feature/AmazingFeature`)
3. Commit les changements (`git commit -m 'Add AmazingFeature'`)
4. Push vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrir une Pull Request

### Standards de Code

- **PSR-12** pour PHP
- **ESLint** pour JavaScript
- **Tests** obligatoires pour nouvelles features
- **Documentation** mise à jour

---

## 📝 Changelog

### Version 1.0.0 (2024-01-15)

#### ✨ Features
- ✅ CRM complet avec score RFM
- ✅ Système de tiers (Regular, VIP, Super VIP)
- ✅ Détection clients à risque
- ✅ Génération contenu IA (GPT-4)
- ✅ Analyse sentiment multi-dimensionnelle
- ✅ Système de campagnes email
- ✅ 5 templates email professionnels
- ✅ Dashboard web moderne Bootstrap 5
- ✅ Application mobile React Native
- ✅ API RESTful complète
- ✅ Tests unitaires et feature

#### 🎨 UI/UX
- ✅ Design moderne avec gradients
- ✅ Animations AOS
- ✅ Charts interactifs
- ✅ Interface mobile native
- ✅ Responsive design

#### 🔧 Technical
- ✅ Laravel 10 + PHP 8.2
- ✅ PostgreSQL 15
- ✅ Redis cache & queues
- ✅ React Native 0.73
- ✅ Scripts de déploiement
- ✅ Documentation complète

---

## 📞 Support

### Ressources

- 📧 **Email:** support@restauboost.com
- 📖 **Documentation:** [API_DOCUMENTATION.md](./API_DOCUMENTATION.md)
- 🐛 **Issues:** [GitHub Issues](https://github.com/haythemsaa/restau/issues)
- 💬 **Discord:** RestauBoost Community (à venir)

### FAQ

**Q: Comment configurer l'API OpenAI?**
A: Ajoutez votre clé dans `.env`: `OPENAI_API_KEY=sk-...`

**Q: L'application mobile se connecte-t-elle au backend?**
A: Oui, configurez l'URL dans `mobile/src/services/api.js`

**Q: Comment activer les queues?**
A: Lancez `php artisan queue:work` ou utilisez Supervisor

**Q: Les emails ne s'envoient pas?**
A: Vérifiez la configuration MAIL_* dans `.env`

---

## 📄 Licence

Ce projet est sous licence propriétaire. Tous droits réservés.

---

## 🎯 Roadmap

### ✅ Phase 1 - MVP (Complété)
- ✅ Backend Laravel complet
- ✅ CRM avec RFM analysis
- ✅ Intelligence artificielle
- ✅ Email marketing
- ✅ Interface web Bootstrap
- ✅ Application mobile React Native
- ✅ Tests et documentation

### 🔄 Phase 2 - Features Avancées (Q1 2024)
- ⏳ Intégration Google Business Profile
- ⏳ Agrégation avis multi-plateformes
- ⏳ Community management
- ⏳ Messagerie unifiée
- ⏳ Analytics avancés
- ⏳ Webhooks

### 📅 Phase 3 - Enterprise (Q2 2024)
- ⏳ Multi-tenant
- ⏳ White-label
- ⏳ API publique avancée
- ⏳ Intégrations tierces (15+)
- ⏳ Certifications sécurité
- ⏳ Expansion internationale

---

## 🏆 Remerciements

- **Laravel Team** - Framework exceptionnel
- **React Native Community** - Écosystème mobile
- **OpenAI** - Intelligence artificielle
- **Bootstrap Team** - UI framework
- **Tous les contributeurs** - Merci !

---

**Projet:** RestauBoost
**Version:** 1.0.0
**Date:** 18 Novembre 2024
**Auteur:** Haythem SAA

Développé avec ❤️ pour révolutionner le marketing digital des restaurants

---

