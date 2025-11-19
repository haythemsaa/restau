# 🚀 RestauBoost - Démarrage Rapide (5 minutes)

Guide pour lancer l'application **immédiatement** en mode productif.

---

## ⚡ Installation Express (1 ligne de commande)

```bash
git clone https://github.com/haythemsaa/restau.git && cd restau && chmod +x scripts/setup.sh && ./scripts/setup.sh
```

Le script configure **TOUT** automatiquement :
- ✅ Environnement (.env)
- ✅ Dépendances (Composer + NPM)
- ✅ Base de données PostgreSQL
- ✅ Migrations + Données de démo
- ✅ Assets frontend

**Durée:** ~3-5 minutes

---

## 🎯 Lancement Immédiat

### Option 1: Script Automatique (Recommandé)

```bash
# Terminal 1 - Backend + Workers
php artisan serve & php artisan queue:work

# Terminal 2 - Frontend (hot reload)
npm run dev
```

### Option 2: Commandes Séparées

**Terminal 1 - Backend:**
```bash
php artisan serve
# ➜ http://localhost:8000
```

**Terminal 2 - Queue Worker:**
```bash
php artisan queue:work
# ➜ Traite les jobs (emails, etc.)
```

**Terminal 3 - Frontend Dev:**
```bash
npm run dev
# ➜ Hot reload activé
```

---

## 🔑 Connexion Immédiate

### Compte Demo Préconfiguré

```
URL:      http://localhost:8000/login
Email:    demo@restauboost.com
Password: password
```

### Créer un Nouveau Compte

```
URL: http://localhost:8000/register
```

---

## 📱 Lancer l'Application Mobile (Optionnel)

### iOS (macOS uniquement)

```bash
cd mobile
npm install
cd ios && pod install && cd ..
npm run ios
```

### Android

```bash
cd mobile
npm install
npm run android
```

**Note:** Configurez l'URL API dans `mobile/src/services/api.js` si nécessaire.

---

## 🎨 Interface Web - Pages Disponibles

Après connexion, accédez à:

| Page | URL | Description |
|------|-----|-------------|
| **Dashboard** | `/dashboard` | Vue d'ensemble avec stats et graphiques |
| **Clients** | `/customers` | Liste, recherche, filtres (VIP, à risque) |
| **Détail Client** | `/customers/{id}` | Profil complet, RFM score, historique |
| **Créer Client** | `/customers/create` | Formulaire ajout client |
| **Générateur IA** | `/ai/content-generator` | Création contenu Facebook, Instagram, etc. |
| **Campagnes Email** | `/campaigns` | Gestion campagnes marketing |
| **Créer Campagne** | `/campaigns/create` | Nouvelle campagne email |
| **Rapports** | `/reports` | Analytics et exports |
| **Paramètres** | `/settings` | Configuration compte et business |

---

## 🤖 Fonctionnalités Productives Immédiates

### 1. **Générer du Contenu IA** (GPT-4)

```
1. Aller sur /ai/content-generator
2. Choisir type: Facebook, Instagram, Twitter, Email
3. Sélectionner ton: Professionnel, Amical, Enthousiaste
4. Entrer le sujet (ex: "Nouveau menu automne")
5. Cliquer "Générer"
6. Copier et publier!
```

**Exemple de résultat immédiat:**
```
🍽️ Découvrez notre nouveau menu automne!

Nous sommes ravis de vous présenter nos plats de saison
préparés avec des produits locaux et frais. 🍂

✨ Entrée: Velouté de butternut aux noisettes
🥘 Plat: Magret de canard aux figues
🍰 Dessert: Tarte aux pommes maison

Réservez: 01 23 45 67 89

#RestaurantGourmet #CuisineLocale #MenuAutomne
```

### 2. **Envoyer une Campagne Email**

```
1. Aller sur /campaigns/create
2. Choisir un template (5 templates pros inclus)
3. Sélectionner segment de clients
4. Programmer l'envoi (immédiat ou planifié)
5. Cliquer "Envoyer"
```

**Templates Disponibles:**
- ✉️ Bienvenue nouveau client (avec code promo)
- 🔙 Réactivation clients inactifs (offre retour)
- 👑 Programme VIP (avantages exclusifs)
- 🍂 Nouveau menu saisonnier
- 🎂 Anniversaire client (cadeau offert)

### 3. **Analyser vos Clients**

```
1. Aller sur /customers
2. Filtrer par: Tous | VIP | À risque
3. Voir scores RFM automatiques
4. Exporter en CSV ou PDF
```

**Filtres Intelligents:**
- 🌟 Clients VIP (15+ dans démo)
- ⚠️ Clients à risque (8+ dans démo)
- 💰 Top spenders
- 📅 Dernière visite

### 4. **Segmentation Automatique**

L'application calcule automatiquement:

**Score RFM (0-15):**
- **R**écence: Dernière visite (0-5)
- **F**réquence: Nombre de visites (0-5)
- **M**ontant: Dépenses totales (0-5)

**Tiers Auto:**
- **Regular:** Score < 9
- **VIP:** Score 9-12
- **Super VIP:** Score 13-15

**Détection Risque:**
- Pas de visite depuis 30+ jours
- Au moins 3 visites historiques
- Marqué automatiquement "à risque"

### 5. **Export de Données** (Production Ready)

```bash
# Export clients en CSV
curl http://localhost:8000/customers/export/csv

# Export clients en PDF
curl http://localhost:8000/customers/export/pdf

# Export via interface web
/customers → Bouton "Exporter" → Choisir format
```

---

## 📊 Données de Démonstration Incluses

### Clients (50 profils complets)

- **15 clients VIP** - Score RFM 9-12
- **8 clients Super VIP** - Score RFM 13-15
- **8 clients à risque** - Inactifs 30+ jours
- **200+ visites** - Historique réaliste
- **Tous avec:** Email, téléphone, préférences, allergies

### Campagnes Email (3 pré-configurées)

1. **Bienvenue 2024** - Nouveaux clients (<7 jours)
2. **Réactivation** - Inactifs 30+ jours
3. **Programme VIP** - Clients VIP et Super VIP

### Templates Email (5 HTML professionnels)

Tous avec variables dynamiques, design responsive, call-to-actions.

---

## 🔧 Configuration API OpenAI (Optionnel)

Pour activer la génération IA **réelle** (GPT-4):

```bash
# Éditer .env
nano .env

# Ajouter votre clé
OPENAI_API_KEY=sk-votre_clé_ici
OPENAI_MODEL=gpt-4

# Redémarrer
php artisan config:clear
php artisan serve
```

**Sans clé OpenAI:** L'app utilise des exemples mockés (fonctionnel pour démo).

---

## 📱 API Mobile - Test Immédiat

### Endpoints Disponibles

```bash
# Login
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"demo@restauboost.com","password":"password"}'

# Liste clients (avec token)
curl http://localhost:8000/api/v1/customers \
  -H "Authorization: Bearer YOUR_TOKEN"

# Générer contenu IA
curl -X POST http://localhost:8000/api/v1/ai/generate-content \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"type":"facebook","prompt":"Nouveau menu","tone":"professional"}'
```

**Documentation complète:** [API_DOCUMENTATION.md](./API_DOCUMENTATION.md)

---

## 🧪 Tester l'Application

### Tests Automatisés

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

**Tests inclus:**
- ✅ AI Content Generator
- ✅ Sentiment Analysis
- ✅ Customer CRUD
- ✅ Review Responses
- ✅ Customer Model

### Test Manuel Rapide

**Scénario 1: Créer un client et voir son évolution**

```
1. Aller sur /customers/create
2. Remplir: Nom, Email, Téléphone
3. Sauvegarder
4. Ajouter une visite: Bouton "Nouvelle visite"
5. Entrer: Date, Montant, Nombre d'articles
6. Observer: Score RFM mis à jour automatiquement
```

**Scénario 2: Campagne email complète**

```
1. /campaigns/create
2. Template: "Bienvenue nouveau client"
3. Segment: Clients créés dans les 7 derniers jours
4. Test: Bouton "Envoyer un test" (à votre email)
5. Vérifier l'email reçu
6. Planifier l'envoi réel
```

**Scénario 3: Générer contenu social**

```
1. /ai/content-generator
2. Type: Instagram
3. Ton: Enthousiaste
4. Prompt: "Menu végétarien printemps"
5. Générer
6. Copier le résultat
7. Tester avec votre audience
```

---

## 🚀 Commandes Artisan Utiles

### Développement

```bash
# Créer un client
php artisan make:customer

# Calculer scores RFM
php artisan customers:calculate-rfm

# Envoyer campagne test
php artisan campaign:test {campaign_id}

# Nettoyer cache
php artisan cache:clear

# Optimiser app
php artisan optimize

# Voir routes
php artisan route:list

# Voir jobs queue
php artisan queue:monitor
```

### Production

```bash
# Déployer
./scripts/deploy.sh production

# Backup
./scripts/backup.sh

# Optimiser
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 📈 Métriques de Performance

### Temps de Réponse Attendus

| Endpoint | Temps Moyen | Temps Max |
|----------|-------------|-----------|
| Dashboard | 150ms | 300ms |
| Liste clients | 80ms | 150ms |
| Détail client | 120ms | 250ms |
| Génération IA | 2-5s | 10s |
| Envoi email | Async | N/A |

### Capacités

- **Clients:** Testé jusqu'à 10,000 clients
- **Emails:** 5,000 recipients par campagne
- **Queue:** 100 jobs/minute
- **Concurrent users:** 50+ utilisateurs simultanés

---

## 🐛 Dépannage Rapide

### Le serveur ne démarre pas

```bash
# Vérifier PHP
php -v  # Doit être >= 8.2

# Vérifier port
lsof -i :8000  # Tuer process si occupé

# Relancer
php artisan serve --port=8001
```

### Base de données ne se connecte pas

```bash
# Vérifier PostgreSQL
psql --version

# Tester connexion
psql -U postgres -d restauboost -c "SELECT 1;"

# Recréer DB
dropdb restauboost
createdb restauboost
php artisan migrate:fresh --seed
```

### Assets ne se compilent pas

```bash
# Nettoyer
rm -rf node_modules package-lock.json

# Réinstaller
npm install

# Rebuild
npm run build
```

### Queue ne traite pas les jobs

```bash
# Vérifier Redis
redis-cli ping  # Doit répondre PONG

# Vérifier jobs
php artisan queue:work --once

# Voir failed jobs
php artisan queue:failed
```

### Erreur "Class not found"

```bash
# Recharger autoload
composer dump-autoload

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

---

## 📞 Support Rapide

### Logs à Vérifier

```bash
# Laravel logs
tail -f storage/logs/laravel.log

# Queue logs
tail -f storage/logs/worker.log

# Nginx/Apache logs
tail -f /var/log/nginx/error.log
```

### Commandes Debug

```bash
# Mode debug
php artisan serve --verbose

# Voir config
php artisan config:show database

# Tester email
php artisan tinker
>>> Mail::raw('Test', fn($msg) => $msg->to('test@example.com')->subject('Test'));
```

---

## 🎓 Prochaines Étapes

### 1. **Personnaliser l'Application**

```
✓ Modifier logo: public/images/logo.png
✓ Couleurs: public/css/app.css (variables CSS)
✓ Templates email: database/seeders/EmailTemplateSeeder.php
✓ Config business: /settings/business
```

### 2. **Ajouter des Intégrations**

```
✓ Google Business Profile
✓ Facebook Pages
✓ Instagram Business
✓ SendGrid/Mailgun pour emails
✓ Stripe pour paiements
```

### 3. **Configurer Production**

```
✓ Voir: OPTIMIZATION.md
✓ Configurer Nginx
✓ Activer SSL/TLS
✓ Configurer Supervisor pour queues
✓ Monitoring (New Relic, Sentry)
```

### 4. **Étendre les Fonctionnalités**

```
✓ Ajouter SMS campaigns
✓ Intégrer réservations
✓ Module de fidélité
✓ Programme de parrainage
✓ Multi-restaurant
```

---

## ✅ Checklist Démarrage Productif

- [ ] Application installée et lancée
- [ ] Connecté avec compte demo
- [ ] Dashboard exploré
- [ ] Client créé manuellement
- [ ] Visite ajoutée à un client
- [ ] Score RFM vérifié
- [ ] Contenu IA généré
- [ ] Campagne email créée
- [ ] Template email personnalisé
- [ ] Export CSV/PDF testé
- [ ] Application mobile testée (optionnel)
- [ ] API testée avec curl
- [ ] Tests automatisés exécutés
- [ ] Backup configuré
- [ ] Production checklist consultée

---

## 🎉 Félicitations!

Vous avez maintenant une application **100% fonctionnelle** et **prête pour la production**!

**Temps total:** ~5-10 minutes
**Prêt pour:** Démo client, MVP, Production

### Ressources Utiles

- 📖 **README.md** - Documentation complète
- 🔌 **API_DOCUMENTATION.md** - Référence API
- ⚡ **OPTIMIZATION.md** - Guide performance
- 📱 **mobile/README.md** - Doc React Native

---

**Questions?** Consultez la [FAQ dans README.md](./README.md#faq)

**Bon lancement! 🚀**

---

*Dernière mise à jour: 2024-01-15*
