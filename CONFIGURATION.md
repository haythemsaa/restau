# Configuration Guide - RestauBoost

Ce guide vous aidera à configurer toutes les fonctionnalités de RestauBoost, notamment l'intégration OpenAI pour les fonctionnalités IA et les autres services externes.

## Table des matières

1. [Prérequis](#prérequis)
2. [Configuration de base](#configuration-de-base)
3. [Configuration OpenAI](#configuration-openai)
4. [Configuration de la base de données](#configuration-de-la-base-de-données)
5. [Configuration Redis](#configuration-redis)
6. [Configuration des emails](#configuration-des-emails)
7. [Configuration Twilio (SMS)](#configuration-twilio-sms)
8. [Variables d'environnement complètes](#variables-denvironnement-complètes)
9. [Initialisation et migration](#initialisation-et-migration)
10. [Tests](#tests)

---

## Prérequis

- PHP 8.2 ou supérieur
- Composer 2.x
- PostgreSQL 15 ou supérieur
- Redis 7.x
- Node.js 18.x ou supérieur
- NPM 9.x ou supérieur

## Configuration de base

1. **Cloner le projet et installer les dépendances**

```bash
# Cloner le repository
git clone https://github.com/votre-org/restauboost.git
cd restauboost

# Installer les dépendances PHP
composer install

# Installer les dépendances Node.js
npm install

# Copier le fichier d'environnement
cp .env.example .env

# Générer la clé d'application
php artisan key:generate
```

2. **Configurer les permissions**

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

## Configuration OpenAI

Les fonctionnalités IA de RestauBoost utilisent l'API OpenAI GPT-4. Voici comment la configurer :

### 1. Obtenir une clé API OpenAI

1. Créez un compte sur [OpenAI Platform](https://platform.openai.com/)
2. Accédez à [API Keys](https://platform.openai.com/api-keys)
3. Cliquez sur "Create new secret key"
4. Copiez votre clé (elle ne sera affichée qu'une fois)

### 2. Configurer les variables d'environnement

Ajoutez ces lignes à votre fichier `.env` :

```env
# OpenAI Configuration
OPENAI_API_KEY=sk-proj-xxxxxxxxxxxxxxxxxxxxxxxxxxxxx
OPENAI_ORGANIZATION=org-xxxxxxxxxxxxxxxxxxxxx  # Optionnel
OPENAI_MODEL=gpt-4                              # Ou gpt-3.5-turbo pour réduire les coûts
```

### 3. Budget et coûts OpenAI

**Tarifs GPT-4 (Nov 2024):**
- Input: $0.03 / 1K tokens
- Output: $0.06 / 1K tokens

**Tarifs GPT-3.5-turbo:**
- Input: $0.0015 / 1K tokens
- Output: $0.002 / 1K tokens

**Estimation pour RestauBoost:**
- Génération de contenu social: ~500 tokens → $0.03 par génération (GPT-4)
- Analyse de sentiment: ~200 tokens → $0.01 par analyse
- Réponse aux avis: ~300 tokens → $0.02 par réponse

**Recommandations:**
- Utilisez GPT-4 pour la génération de contenu (meilleure qualité)
- Utilisez GPT-3.5-turbo pour l'analyse de sentiment (suffisant et 20x moins cher)
- Configurez des limites dans votre compte OpenAI
- Budget recommandé pour démarrer: $50-100/mois

### 4. Fonctionnalités IA disponibles

Une fois configuré, vous aurez accès à :

✅ **Génération de contenu social media**
- Posts optimisés par plateforme (Facebook, Instagram, Twitter, LinkedIn)
- Suggestions de hashtags intelligents
- Recommandations de timing de publication

✅ **Analyse de sentiment avancée**
- Score global + aspects détaillés (nourriture, service, ambiance, prix)
- Détection d'émotions (joie, déception, colère, surprise)
- Priorisation automatique des avis
- Insights actionnables

✅ **Réponses automatiques aux avis**
- Génération de réponses personnalisées
- Plusieurs tons disponibles (professionnel, chaleureux, enthousiaste)
- Validation qualité automatique
- Adaptation selon la note (1-5 étoiles)

## Configuration de la base de données

### 1. Créer la base de données PostgreSQL

```bash
# Se connecter à PostgreSQL
sudo -u postgres psql

# Créer la base de données et l'utilisateur
CREATE DATABASE restauboost;
CREATE USER restauboost_user WITH ENCRYPTED PASSWORD 'votre_mot_de_passe_securise';
GRANT ALL PRIVILEGES ON DATABASE restauboost TO restauboost_user;
\q
```

### 2. Configurer .env

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=restauboost
DB_USERNAME=restauboost_user
DB_PASSWORD=votre_mot_de_passe_securise
```

### 3. Exécuter les migrations

```bash
# Exécuter toutes les migrations
php artisan migrate

# Optionnel: Charger les données de test
php artisan db:seed
```

## Configuration Redis

Redis est utilisé pour le cache, les queues et les sessions.

### 1. Installer Redis

```bash
# Ubuntu/Debian
sudo apt-get install redis-server

# macOS
brew install redis

# Démarrer Redis
sudo systemctl start redis
# ou sur macOS
brew services start redis
```

### 2. Configurer .env

```env
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
REDIS_CLIENT=phpredis  # ou 'predis'

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
```

### 3. Démarrer les workers

```bash
# Pour le traitement des queues en arrière-plan
php artisan queue:work --tries=3 --timeout=90

# Recommandé: Utiliser Horizon pour une meilleure gestion
php artisan horizon
```

## Configuration des emails

### Option 1: Mailtrap (Développement)

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=hello@restauboost.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Option 2: SendGrid (Production)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=SG.xxxxxxxxxxxxxxxxxxxxx
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@votrerestaurant.com
MAIL_FROM_NAME="RestauBoost"
```

### Option 3: Amazon SES (Production)

```env
MAIL_MAILER=ses
AWS_ACCESS_KEY_ID=your_access_key
AWS_SECRET_ACCESS_KEY=your_secret_key
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your_bucket
```

## Configuration Twilio (SMS)

Pour envoyer des SMS aux clients (notifications, rappels, etc.)

### 1. Obtenir les identifiants Twilio

1. Créez un compte sur [Twilio](https://www.twilio.com)
2. Obtenez votre Account SID et Auth Token depuis le [Console Dashboard](https://console.twilio.com)
3. Achetez un numéro de téléphone Twilio

### 2. Configurer .env

```env
TWILIO_SID=ACxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
TWILIO_TOKEN=your_auth_token
TWILIO_FROM=+15551234567  # Votre numéro Twilio
```

### 3. Tarifs Twilio (Indicatif)

- SMS en France: ~$0.09 par message
- SMS aux USA: ~$0.0079 par message
- Budget recommandé: $20-50/mois pour démarrer

## Variables d'environnement complètes

Voici un exemple complet de fichier `.env` pour la production :

```env
# Application
APP_NAME=RestauBoost
APP_ENV=production
APP_KEY=base64:xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx=
APP_DEBUG=false
APP_URL=https://app.restauboost.com

# Base de données
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=restauboost
DB_USERNAME=restauboost_user
DB_PASSWORD=votre_mot_de_passe_securise

# Redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

# OpenAI
OPENAI_API_KEY=sk-proj-xxxxxxxxxxxxxxxxxxxxxxxxxxxxx
OPENAI_ORGANIZATION=org-xxxxxxxxxxxxxxxxxxxxx
OPENAI_MODEL=gpt-4

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=SG.xxxxxxxxxxxxxxxxxxxxx
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@restauboost.com
MAIL_FROM_NAME="${APP_NAME}"

# Twilio
TWILIO_SID=ACxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
TWILIO_TOKEN=your_auth_token
TWILIO_FROM=+15551234567

# Session
SESSION_LIFETIME=120
SESSION_ENCRYPT=true

# Logging
LOG_CHANNEL=stack
LOG_LEVEL=info
```

## Initialisation et migration

### 1. Première installation complète

```bash
# 1. Migrer la base de données
php artisan migrate

# 2. Créer les segments clients prédéfinis
php artisan db:seed --class=CustomerSegmentSeeder

# 3. Créer un utilisateur admin
php artisan tinker
>>> $user = App\Models\User::create([
...   'name' => 'Admin',
...   'email' => 'admin@restauboost.com',
...   'password' => bcrypt('password'),
... ]);

# 4. Compiler les assets frontend
npm run build

# 5. Optimiser pour la production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Démarrer les workers
php artisan horizon
```

### 2. Données de démonstration (Optionnel)

```bash
# Générer des données de test
php artisan db:seed

# Ou générer des données spécifiques
php artisan tinker
>>> App\Models\Customer::factory(50)->create();
>>> App\Models\CustomerVisit::factory(200)->create();
>>> App\Models\Review::factory(100)->create();
```

## Tests

### 1. Configuration de l'environnement de test

Créez un fichier `.env.testing` :

```env
APP_ENV=testing
DB_CONNECTION=pgsql
DB_DATABASE=restauboost_test
CACHE_DRIVER=array
QUEUE_CONNECTION=sync
SESSION_DRIVER=array
```

### 2. Créer la base de données de test

```bash
sudo -u postgres psql
CREATE DATABASE restauboost_test;
GRANT ALL PRIVILEGES ON DATABASE restauboost_test TO restauboost_user;
\q
```

### 3. Exécuter les tests

```bash
# Tous les tests
php artisan test

# Tests spécifiques
php artisan test --filter=CustomerTest
php artisan test --filter=AITest

# Avec couverture de code
php artisan test --coverage
```

## Vérification de la configuration

### 1. Vérifier que tout fonctionne

```bash
# Test de la connexion base de données
php artisan tinker
>>> DB::connection()->getPdo();

# Test de Redis
>>> Redis::ping();

# Test OpenAI (créez un fichier test)
php artisan tinker
>>> $service = new App\Services\AI\ContentGeneratorService();
>>> $result = $service->generateSocialPost([
...   'business_id' => 'test',
...   'platform' => 'instagram',
...   'theme' => 'Test',
... ]);
>>> dd($result);
```

### 2. Monitoring et logs

```bash
# Voir les logs en temps réel
tail -f storage/logs/laravel.log

# Voir le statut de Horizon
php artisan horizon:status

# Voir les jobs en échec
php artisan queue:failed
```

## Dépannage

### Problème: "Class 'Redis' not found"

```bash
# Installer l'extension PHP Redis
sudo apt-get install php-redis
sudo systemctl restart php8.2-fpm
```

### Problème: OpenAI API timeout

```env
# Augmenter le timeout dans config/services.php
'openai' => [
    'timeout' => 60, // secondes
],
```

### Problème: Migrations échouent

```bash
# Réinitialiser la base de données
php artisan migrate:fresh

# Ou rollback puis migrate
php artisan migrate:rollback
php artisan migrate
```

## Sécurité en production

### 1. Checklist de sécurité

- [ ] `APP_DEBUG=false` dans `.env`
- [ ] `APP_ENV=production` dans `.env`
- [ ] Utilisez HTTPS (certificat SSL)
- [ ] Configurez le pare-feu (UFW, iptables)
- [ ] Utilisez des mots de passe forts
- [ ] Activez le rate limiting
- [ ] Configurez les backups automatiques
- [ ] Mettez en place un monitoring (Sentry, etc.)

### 2. Backups

```bash
# Backup base de données
pg_dump restauboost > backup_$(date +%Y%m%d).sql

# Backup fichiers
tar -czf backup_files_$(date +%Y%m%d).tar.gz storage/ .env

# Automatiser avec cron
0 2 * * * /path/to/backup_script.sh
```

## Support

Pour toute question ou problème :

- Documentation complète: `/docs/README.md`
- API Documentation: `/docs/API.md`
- Issues GitHub: https://github.com/votre-org/restauboost/issues

---

**Version:** 1.0.0
**Dernière mise à jour:** Novembre 2024
