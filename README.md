# RestauBoost - Plateforme Marketing Digital pour Restaurants

![Laravel](https://img.shields.io/badge/Laravel-10.x-red.svg)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-15-blue.svg)
![Vue.js](https://img.shields.io/badge/Vue.js-3.x-green.svg)

**RestauBoost** est une plateforme SaaS complète de marketing digital pour restaurants, inspirée de Malou.io. Elle offre une solution tout-en-un pour gérer la présence en ligne, l'e-réputation, les réseaux sociaux, la messagerie client et l'analyse de performance.

## 🎯 Vision du Projet

Révolutionner le marketing digital des restaurants en centralisant tous les outils nécessaires dans une seule plateforme intuitive et puissante, permettant aux restaurateurs d'économiser du temps et d'augmenter significativement leur visibilité en ligne.

## 📋 Modules Principaux

### 1. **Référencement Local (SEO)**
- Gestion du profil Google Business
- Optimisation multi-plateformes (Google, Bing, Apple Maps)
- Suivi des positions et mots-clés
- Génération automatique de contenu SEO

### 2. **E-Réputation**
- Agrégation des avis de 15+ plateformes
- Analyse de sentiment IA
- Génération automatique de réponses
- Alertes en temps réel

### 3. **Community Management**
- Publication multi-réseaux (Facebook, Instagram, TikTok)
- Calendrier éditorial
- Suggestions de contenu IA
- Analytics détaillés

### 4. **Messagerie Unifiée**
- Centralisation de 12+ canaux (Messenger, WhatsApp, Instagram DM, etc.)
- Chatbot intelligent
- Gestion d'équipe avec assignation
- SLA et temps de réponse

### 5. **Présence Management**
- Gestion profil unique pour 30+ plateformes
- Détection incohérences NAP
- Éditeur de menus digital
- Bibliothèque photos avec IA

### 6. **Analytics & Reporting**
- Dashboard 360° performance
- Calcul ROI automatique
- Rapports personnalisés
- Insights actionnables

### 7. **Intelligence Artificielle**
- Génération de contenu
- Analyse sentiment avancée
- Optimisation automatique
- Personnalisation client

### 8. **Intégrations**
- 30+ intégrations natives
- API publique RESTful
- Webhooks temps réel
- CRM et outils marketing

### 9. **Administration**
- Gestion utilisateurs et permissions
- Facturation automatique
- Support multi-niveaux
- Sécurité et conformité RGPD

## 🛠 Stack Technique

### Backend
- **Framework:** Laravel 10.x (PHP 8.2+)
- **Architecture:** MVC + Repository Pattern
- **API:** RESTful + Laravel Sanctum
- **Queue:** Laravel Horizon (Redis)
- **Cache:** Redis
- **Database:** PostgreSQL 15

### Frontend
- **Framework:** Vue.js 3 + TypeScript
- **State:** Pinia
- **UI:** Tailwind CSS + Headless UI
- **Charts:** Chart.js / ApexCharts
- **Real-time:** Laravel Echo + Pusher

### Infrastructure
- **Hosting:** AWS / GCP
- **CDN:** CloudFront / Cloudflare
- **Storage:** S3
- **Monitoring:** Sentry, New Relic
- **CI/CD:** GitHub Actions

## 📦 Installation

### Prérequis
- PHP 8.2+
- Composer
- PostgreSQL 15
- Redis
- Node.js 18+

### Étapes d'installation

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
Modifier le fichier `.env` avec vos paramètres PostgreSQL:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=restauboost
DB_USERNAME=postgres
DB_PASSWORD=votre_password
```

5. **Configurer Redis**
```env
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
```

6. **Exécuter les migrations**
```bash
php artisan migrate
```

7. **Installer les dépendances Node.js**
```bash
npm install
```

8. **Compiler les assets**
```bash
npm run dev
# ou pour production
npm run build
```

9. **Démarrer les workers**
```bash
php artisan horizon
```

10. **Lancer le serveur de développement**
```bash
php artisan serve
```

L'application sera disponible sur `http://localhost:8000`

## 🗄 Schéma de Base de Données

### Tables Principales

- **business_groups** - Groupes de restaurants (chaînes)
- **businesses** - Établissements individuels
- **users** - Utilisateurs de la plateforme
- **business_users** - Table pivot (accès utilisateurs)
- **reviews** - Avis clients agrégés
- **social_posts** - Publications réseaux sociaux
- **conversations** - Conversations clients
- **messages** - Messages individuels
- **analytics_daily** - Métriques quotidiennes

### Relations Clés
- Un **Business** appartient à un **BusinessGroup** (optionnel)
- Un **Business** a plusieurs **Users** (many-to-many)
- Un **Business** a plusieurs **Reviews**, **SocialPosts**, **Conversations**, **Analytics**
- Une **Conversation** a plusieurs **Messages**

## 🔐 Sécurité

- **Authentification:** Laravel Sanctum + MFA
- **Encryption:** TLS 1.3, AES-256
- **RGPD:** Conformité complète
- **OWASP:** Protection Top 10
- **Rate Limiting:** Protection brute force
- **Audit Logs:** Traçabilité complète

## 📊 Roadmap

### Phase 1 - MVP (6 mois) ✅
- ✅ Setup infrastructure
- ✅ Authentification & gestion utilisateurs
- ✅ Schéma de base de données
- ✅ Modèles Eloquent avec relations
- ⏳ Modules SEO, E-Réputation, Community Management
- ⏳ Analytics dashboards

### Phase 2 - Growth (6 mois)
- ⏳ IA & Automation
- ⏳ Chatbot intelligent
- ⏳ Application mobile (Flutter)
- ⏳ Multi-établissements avancé

### Phase 3 - Enterprise (6 mois)
- ⏳ White-label
- ⏳ API publique avancée
- ⏳ Certifications (ISO 27001, SOC2)
- ⏳ Expansion internationale

## 🎯 KPIs Cibles

- **+320%** ROI moyen
- **+18%** de nouveaux clients
- **+350%** de visibilité en ligne
- **+0.5★** amélioration note moyenne
- **28h/mois** de temps économisé
- **<30min** temps de première réponse
- **>90%** taux de réponse messages

## 📚 Documentation

Pour plus de détails, consultez les cahiers des charges :
- `CAHIER_CHARGES_COMPLET.md` - Spécifications complètes
- `cahier_charges_partie1.md` - Modules 1-3 détaillés
- `LISEZMOI.txt` - Structure documentaire

## 🤝 Contribution

Les contributions sont les bienvenues ! Merci de :
1. Fork le projet
2. Créer une branche feature (`git checkout -b feature/AmazingFeature`)
3. Commit les changements (`git commit -m 'Add AmazingFeature'`)
4. Push vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrir une Pull Request

## 📝 License

Ce projet est sous licence propriétaire. Tous droits réservés.

## 📧 Contact

**Projet:** RestauBoost
**Date:** 18 Novembre 2025
**Version:** 1.0 - MVP en développement

---

Développé avec ❤️ pour révolutionner le marketing digital des restaurants
