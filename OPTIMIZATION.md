# RestauBoost - Guide d'Optimisation des Performances

## Table des Matières

1. [Configuration Laravel](#configuration-laravel)
2. [Optimisations Base de Données](#optimisations-base-de-données)
3. [Cache & Redis](#cache--redis)
4. [Queue & Jobs](#queue--jobs)
5. [Frontend](#frontend)
6. [Production](#production)

---

## Configuration Laravel

### Caching de Configuration

```bash
# Cache toutes les configurations
php artisan config:cache

# Cache les routes
php artisan route:cache

# Cache les vues
php artisan view:cache

# Optimisation complète
php artisan optimize
```

### Variables d'Environnement Recommandées

```env
# Production
APP_ENV=production
APP_DEBUG=false

# Cache
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Optimizations
REDIS_CLIENT=phpredis
DB_CONNECTION=pgsql
```

---

## Optimisations Base de Données

### Indexes PostgreSQL

Les migrations incluent déjà des index optimisés:

**Customers:**
- `idx_customers_email` - Recherche par email
- `idx_customers_phone` - Recherche par téléphone
- `idx_customers_tier` - Filtrage par tier
- `idx_customers_at_risk` - Filtrage clients à risque

**Customer Visits:**
- `idx_customer_visits_customer_id` - Requêtes par client
- `idx_customer_visits_date` - Requêtes par date
- `idx_customer_visits_amount` - Tri par montant

### Requêtes Optimisées

```php
// Eager Loading
$customers = Customer::with(['visits', 'segments'])->get();

// Select spécifique
$customers = Customer::select(['id', 'full_name', 'email'])->get();

// Chunking pour gros volumes
Customer::chunk(200, function ($customers) {
    foreach ($customers as $customer) {
        // Process customer
    }
});

// Pagination
$customers = Customer::paginate(15);
```

### Indexes Supplémentaires (si nécessaire)

```sql
-- Index composite pour filtres fréquents
CREATE INDEX idx_customers_tier_active ON customers(tier, at_risk)
WHERE deleted_at IS NULL;

-- Index pour recherche full-text
CREATE INDEX idx_customers_fulltext ON customers
USING gin(to_tsvector('french', full_name || ' ' || email));
```

---

## Cache & Redis

### Configuration Redis Optimale

**config/database.php:**
```php
'redis' => [
    'client' => env('REDIS_CLIENT', 'phpredis'), // Plus rapide que predis

    'options' => [
        'cluster' => env('REDIS_CLUSTER', 'redis'),
        'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_database_'),
    ],

    'default' => [
        'url' => env('REDIS_URL'),
        'host' => env('REDIS_HOST', '127.0.0.1'),
        'password' => env('REDIS_PASSWORD'),
        'port' => env('REDIS_PORT', '6379'),
        'database' => env('REDIS_DB', '0'),
        'read_timeout' => 60,
        'retry_interval' => 100,
    ],

    'cache' => [
        'url' => env('REDIS_URL'),
        'host' => env('REDIS_HOST', '127.0.0.1'),
        'password' => env('REDIS_PASSWORD'),
        'port' => env('REDIS_PORT', '6379'),
        'database' => env('REDIS_CACHE_DB', '1'),
    ],
];
```

### Stratégies de Cache

**1. Cache de Requêtes:**
```php
// Cache customer count for 1 hour
$customerCount = Cache::remember('stats:customer_count', 3600, function () {
    return Customer::count();
});

// Cache customer with RFM score
$customer = Cache::remember("customer:{$id}:rfm", 300, function () use ($id) {
    return Customer::with('rfmScore')->find($id);
});
```

**2. Tags de Cache:**
```php
// Cache with tags (Redis only)
Cache::tags(['customers', 'stats'])->put('vip_count', $count, 3600);

// Flush specific tags
Cache::tags('customers')->flush();
```

**3. Model Caching:**
```php
// In Customer model
protected static function boot()
{
    parent::boot();

    static::updated(function ($customer) {
        Cache::forget("customer:{$customer->id}");
        Cache::tags('customers')->flush();
    });
}
```

---

## Queue & Jobs

### Configuration Optimale

```env
QUEUE_CONNECTION=redis
QUEUE_RETRY_AFTER=90
QUEUE_FAILED_DRIVER=database
```

### Supervision avec Supervisor

**/etc/supervisor/conf.d/restauboost-worker.conf:**
```ini
[program:restauboost-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/restauboost/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=4
redirect_stderr=true
stdout_logfile=/path/to/restauboost/storage/logs/worker.log
stopwaitsecs=3600
```

Démarrer:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start restauboost-worker:*
```

### Jobs Optimisés

```php
class SendCampaignEmail implements ShouldQueue
{
    use Batchable, Queueable, Dispatchable, InteractsWithQueue;

    public $tries = 3;
    public $timeout = 120;
    public $backoff = [60, 300, 900]; // 1min, 5min, 15min

    public function handle()
    {
        // Job logic
    }

    public function failed($exception)
    {
        // Handle failure
        Log::error('Campaign email failed', [
            'exception' => $exception->getMessage()
        ]);
    }
}
```

---

## Frontend

### Optimisation Assets

**1. Vite Build:**
```bash
npm run build
```

**2. Asset Compression:**
```javascript
// vite.config.js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import compression from 'vite-plugin-compression';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        compression({
            algorithm: 'gzip',
            ext: '.gz',
        }),
    ],
    build: {
        minify: 'terser',
        terserOptions: {
            compress: {
                drop_console: true,
            },
        },
    },
});
```

**3. Image Optimization:**
```bash
# Install optimizer
npm install -D vite-plugin-imagemin

# Add to vite.config.js
import viteImagemin from 'vite-plugin-imagemin';

plugins: [
    viteImagemin({
        gifsicle: { optimizationLevel: 7 },
        optipng: { optimizationLevel: 7 },
        mozjpeg: { quality: 80 },
        svgo: { plugins: [{ removeViewBox: false }] },
    }),
]
```

### CDN pour Assets Statiques

```env
ASSET_URL=https://cdn.restauboost.com
```

### Lazy Loading

```javascript
// Lazy load charts
const Chart = () => import('chart.js');

// Use when needed
const chart = await Chart();
```

---

## Production

### Nginx Configuration

**/etc/nginx/sites-available/restauboost:**
```nginx
server {
    listen 80;
    server_name restauboost.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name restauboost.com;
    root /var/www/restauboost/public;

    ssl_certificate /path/to/cert.pem;
    ssl_certificate_key /path/to/key.pem;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";

    # Gzip Compression
    gzip on;
    gzip_vary on;
    gzip_proxied any;
    gzip_comp_level 6;
    gzip_types text/plain text/css text/xml text/javascript
               application/json application/javascript application/xml+rss;

    # Cache static assets
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;

        # Increase timeouts for long-running requests
        fastcgi_read_timeout 300;
        fastcgi_send_timeout 300;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### PHP-FPM Optimization

**/etc/php/8.2/fpm/pool.d/www.conf:**
```ini
pm = dynamic
pm.max_children = 50
pm.start_servers = 10
pm.min_spare_servers = 5
pm.max_spare_servers = 15
pm.max_requests = 500

; Optimize memory
php_admin_value[memory_limit] = 256M
php_admin_value[upload_max_filesize] = 50M
php_admin_value[post_max_size] = 50M

; OPcache
php_admin_value[opcache.enable] = 1
php_admin_value[opcache.memory_consumption] = 128
php_admin_value[opcache.interned_strings_buffer] = 8
php_admin_value[opcache.max_accelerated_files] = 10000
php_admin_value[opcache.revalidate_freq] = 60
php_admin_value[opcache.validate_timestamps] = 0
```

### PostgreSQL Tuning

**/etc/postgresql/15/main/postgresql.conf:**
```conf
# Memory
shared_buffers = 256MB
effective_cache_size = 1GB
maintenance_work_mem = 64MB
work_mem = 16MB

# Connections
max_connections = 200

# WAL
wal_buffers = 16MB
checkpoint_completion_target = 0.9

# Query Planning
random_page_cost = 1.1
effective_io_concurrency = 200

# Logging (for monitoring)
log_min_duration_statement = 1000
log_line_prefix = '%t [%p]: [%l-1] user=%u,db=%d,app=%a,client=%h '
log_checkpoints = on
log_connections = on
log_disconnections = on
log_lock_waits = on
```

### Monitoring

**1. Laravel Telescope (Development Only):**
```bash
composer require laravel/telescope --dev
php artisan telescope:install
php artisan migrate
```

**2. Laravel Horizon (Queue Monitoring):**
```bash
composer require laravel/horizon
php artisan horizon:install
php artisan migrate
```

**3. New Relic / DataDog:**
```env
NEW_RELIC_LICENSE_KEY=your_key
NEW_RELIC_APP_NAME=RestauBoost
```

### Performance Checklist

#### Before Deployment
- [ ] Run `php artisan optimize`
- [ ] Run `npm run build`
- [ ] Enable OPcache
- [ ] Configure Redis
- [ ] Set up queue workers
- [ ] Configure database indexes
- [ ] Enable Gzip compression
- [ ] Set up CDN for assets
- [ ] Configure SSL/TLS
- [ ] Set `APP_DEBUG=false`
- [ ] Set `APP_ENV=production`

#### After Deployment
- [ ] Monitor queue workers
- [ ] Check error logs
- [ ] Monitor database queries
- [ ] Monitor cache hit ratio
- [ ] Monitor response times
- [ ] Set up backups
- [ ] Configure monitoring alerts
- [ ] Test all critical features

---

## Performance Metrics

### Target Metrics

- **Page Load Time:** < 2 seconds
- **API Response Time:** < 200ms
- **Time to First Byte (TTFB):** < 500ms
- **Database Query Time:** < 50ms average
- **Cache Hit Ratio:** > 80%
- **Queue Processing:** < 5 minutes for batch jobs

### Tools for Monitoring

- **Laravel Debugbar** (Development)
- **Laravel Telescope** (Development)
- **Laravel Horizon** (Queue monitoring)
- **New Relic** (APM)
- **Blackfire.io** (Profiling)
- **Google PageSpeed Insights**
- **GTmetrix**

---

**Dernière mise à jour:** 2024-01-15
