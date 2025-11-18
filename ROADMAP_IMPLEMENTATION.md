# Plan d'Implémentation - RestauBoost 2.0

## Phase 1 : Intelligence Artificielle (Mois 1-3) 🤖

### 1.1 Génération de Contenu Social Media

#### Backend Laravel
```bash
composer require openai-php/laravel
composer require anthropic-php/anthropic-sdk
```

**Nouvelle Structure:**
```
app/
├── Services/
│   ├── AI/
│   │   ├── OpenAIService.php
│   │   ├── ContentGeneratorService.php
│   │   ├── HashtagGeneratorService.php
│   │   └── ImageDescriptionService.php
```

**Code à ajouter:**

```php
// app/Services/AI/OpenAIService.php
class OpenAIService
{
    public function generateSocialPost(array $params): string
    {
        $prompt = $this->buildPrompt($params);

        $response = OpenAI::chat()->create([
            'model' => 'gpt-4',
            'messages' => [
                ['role' => 'system', 'content' => 'Tu es un expert en marketing pour restaurants français.'],
                ['role' => 'user', 'content' => $prompt]
            ],
            'temperature' => 0.7,
        ]);

        return $response->choices[0]->message->content;
    }

    private function buildPrompt(array $params): string
    {
        return "Crée une publication engageante pour {$params['platform']}
                sur le thème : {$params['theme']}
                Ton du message : {$params['tone']}
                Public cible : {$params['audience']}
                Inclus des emojis pertinents et 3-5 hashtags.";
    }
}

// app/Http/Controllers/Api/AIController.php
class AIController extends Controller
{
    public function generateContent(Request $request)
    {
        $validated = $request->validate([
            'business_id' => 'required|exists:businesses,id',
            'platform' => 'required|in:facebook,instagram,twitter,linkedin',
            'theme' => 'required|string',
            'tone' => 'required|in:professional,friendly,casual,enthusiastic',
            'audience' => 'nullable|string',
        ]);

        $content = app(OpenAIService::class)->generateSocialPost($validated);

        return response()->json([
            'content' => $content,
            'suggestions' => [
                'best_time' => $this->getBestPostingTime($validated['platform']),
                'hashtags' => $this->extractHashtags($content),
            ]
        ]);
    }
}
```

**Routes API:**
```php
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::post('/ai/generate-content', [AIController::class, 'generateContent']);
    Route::post('/ai/generate-response', [AIController::class, 'generateReviewResponse']);
    Route::post('/ai/analyze-sentiment', [AIController::class, 'analyzeSentiment']);
    Route::post('/ai/suggest-hashtags', [AIController::class, 'suggestHashtags']);
});
```

**Frontend Vue.js:**
```vue
<!-- resources/js/components/AI/ContentGenerator.vue -->
<template>
  <div class="card">
    <h3 class="text-xl font-bold mb-4">🤖 Générateur de Contenu IA</h3>

    <form @submit.prevent="generateContent" class="space-y-4">
      <div>
        <label>Plateforme</label>
        <select v-model="form.platform" class="input">
          <option value="facebook">Facebook</option>
          <option value="instagram">Instagram</option>
          <option value="twitter">Twitter</option>
          <option value="linkedin">LinkedIn</option>
        </select>
      </div>

      <div>
        <label>Thème</label>
        <input v-model="form.theme" class="input"
               placeholder="Ex: Nouveau menu d'automne" />
      </div>

      <div>
        <label>Ton</label>
        <select v-model="form.tone" class="input">
          <option value="professional">Professionnel</option>
          <option value="friendly">Amical</option>
          <option value="casual">Décontracté</option>
          <option value="enthusiastic">Enthousiaste</option>
        </select>
      </div>

      <button type="submit" class="btn btn-primary" :disabled="loading">
        {{ loading ? '⏳ Génération...' : '✨ Générer avec IA' }}
      </button>
    </form>

    <div v-if="generatedContent" class="mt-6 p-4 bg-blue-50 rounded-lg">
      <h4 class="font-bold mb-2">Contenu généré :</h4>
      <p class="whitespace-pre-wrap">{{ generatedContent }}</p>

      <div class="mt-4 flex space-x-2">
        <button @click="useContent" class="btn btn-primary btn-sm">
          Utiliser ce contenu
        </button>
        <button @click="regenerate" class="btn btn-secondary btn-sm">
          🔄 Régénérer
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive } from 'vue';
import { api } from '@/services/api';

const form = reactive({
  platform: 'instagram',
  theme: '',
  tone: 'friendly',
  audience: 'food lovers'
});

const loading = ref(false);
const generatedContent = ref('');

async function generateContent() {
  loading.value = true;
  try {
    const response = await api.post('/ai/generate-content', form);
    generatedContent.value = response.data.content;
  } catch (error) {
    console.error('Generation failed:', error);
  } finally {
    loading.value = false;
  }
}
</script>
```

---

### 1.2 Analyse de Sentiment Avancée

**Installation ML Tools:**
```bash
pip install transformers torch scikit-learn pandas
```

**Python Microservice (Flask/FastAPI):**

```python
# ml_service/sentiment_analyzer.py
from transformers import pipeline
from typing import Dict, List

class SentimentAnalyzer:
    def __init__(self):
        # Modèle français optimisé pour restaurants
        self.sentiment_model = pipeline(
            "sentiment-analysis",
            model="nlptown/bert-base-multilingual-uncased-sentiment"
        )

        self.aspect_extractor = pipeline(
            "ner",
            model="Jean-Baptiste/camembert-ner"
        )

    def analyze(self, text: str) -> Dict:
        # Sentiment global
        sentiment_result = self.sentiment_model(text)[0]

        # Extraction aspects (nourriture, service, ambiance, prix)
        aspects = self.extract_aspects(text)

        # Détection émotions
        emotions = self.detect_emotions(text)

        return {
            'overall_sentiment': self.normalize_sentiment(sentiment_result),
            'aspects': aspects,
            'emotions': emotions,
            'actionable_insights': self.generate_insights(aspects, emotions),
            'priority': self.calculate_priority(sentiment_result, emotions)
        }

    def extract_aspects(self, text: str) -> Dict:
        # Catégorisation par aspect
        aspects = {
            'food': {'sentiment': 0, 'mentions': []},
            'service': {'sentiment': 0, 'mentions': []},
            'ambiance': {'sentiment': 0, 'mentions': []},
            'price': {'sentiment': 0, 'mentions': []}
        }

        # Keywords par catégorie
        keywords = {
            'food': ['plat', 'menu', 'cuisine', 'saveur', 'goût', 'délicieux'],
            'service': ['serveur', 'service', 'accueil', 'personnel', 'attente'],
            'ambiance': ['ambiance', 'décor', 'cadre', 'musique', 'atmosphère'],
            'price': ['prix', 'cher', 'rapport qualité', 'tarif', 'addition']
        }

        text_lower = text.lower()
        for aspect, words in keywords.items():
            for word in words:
                if word in text_lower:
                    # Analyser sentiment de la phrase contenant le mot
                    aspects[aspect]['mentions'].append(word)
                    # TODO: Analyse de sentiment contextuelle

        return aspects

    def detect_emotions(self, text: str) -> List[str]:
        emotions = []

        emotion_keywords = {
            'joy': ['content', 'heureux', 'ravi', 'excellent', 'génial'],
            'disappointment': ['déçu', 'dommage', 'malheureusement'],
            'anger': ['inacceptable', 'scandaleux', 'inadmissible'],
            'surprise': ['surpris', 'étonnant', 'inattendu']
        }

        text_lower = text.lower()
        for emotion, keywords in emotion_keywords.items():
            if any(keyword in text_lower for keyword in keywords):
                emotions.append(emotion)

        return emotions

    def generate_insights(self, aspects: Dict, emotions: List) -> List[str]:
        insights = []

        # Analyse des aspects négatifs
        for aspect, data in aspects.items():
            if data['sentiment'] < 0:
                insights.append(f"⚠️ Point d'amélioration: {aspect}")

        # Détection de clients à risque
        if 'anger' in emotions or 'disappointment' in emotions:
            insights.append("🚨 Client mécontent - Action urgente requise")

        return insights

    def calculate_priority(self, sentiment: Dict, emotions: List) -> str:
        if 'anger' in emotions:
            return 'URGENT'
        elif sentiment['label'] == '1 star' or sentiment['label'] == '2 stars':
            return 'HIGH'
        elif sentiment['label'] == '3 stars':
            return 'MEDIUM'
        else:
            return 'LOW'

# API Endpoint
from fastapi import FastAPI
app = FastAPI()

analyzer = SentimentAnalyzer()

@app.post("/analyze")
async def analyze_sentiment(text: str):
    return analyzer.analyze(text)
```

**Intégration Laravel:**
```php
// app/Services/AI/SentimentAnalysisService.php
class SentimentAnalysisService
{
    protected string $mlServiceUrl;

    public function __construct()
    {
        $this->mlServiceUrl = config('services.ml_service.url');
    }

    public function analyze(string $text): array
    {
        $response = Http::post($this->mlServiceUrl . '/analyze', [
            'text' => $text
        ]);

        return $response->json();
    }

    public function analyzeReview(Review $review): void
    {
        $analysis = $this->analyze($review->text);

        $review->update([
            'sentiment_score' => $analysis['overall_sentiment']['score'],
            'sentiment_aspects' => $analysis['aspects'],
            'emotions' => $analysis['emotions'],
            'priority' => $analysis['priority'],
        ]);

        // Créer alerte si urgent
        if ($analysis['priority'] === 'URGENT') {
            $this->createUrgentAlert($review);
        }
    }

    private function createUrgentAlert(Review $review): void
    {
        Notification::send(
            $review->business->users,
            new UrgentReviewNotification($review)
        );
    }
}
```

**Migration pour nouveaux champs:**
```php
Schema::table('reviews', function (Blueprint $table) {
    $table->json('sentiment_aspects')->nullable();
    $table->json('emotions')->nullable();
    $table->enum('priority', ['LOW', 'MEDIUM', 'HIGH', 'URGENT'])->default('MEDIUM');
    $table->text('ai_insights')->nullable();
});
```

---

### 1.3 Réponses Automatiques aux Avis

```php
// app/Services/AI/ReviewResponseService.php
class ReviewResponseService
{
    public function generateResponse(Review $review): string
    {
        $context = $this->buildContext($review);

        $prompt = "
            Génère une réponse professionnelle à cet avis client:

            Avis: {$review->text}
            Note: {$review->rating}/5
            Sentiment: {$review->sentiment_score}
            Restaurant: {$review->business->name}

            Consignes:
            - Ton professionnel et chaleureux
            - Remercie le client
            - Adresse les points spécifiques mentionnés
            - {$this->getToneGuideline($review->rating)}
            - Signe avec le nom du restaurant
            - Maximum 150 mots
        ";

        $response = OpenAI::chat()->create([
            'model' => 'gpt-4',
            'messages' => [
                ['role' => 'system', 'content' => 'Tu es le responsable d\'un restaurant qui répond aux avis clients.'],
                ['role' => 'user', 'content' => $prompt]
            ],
            'temperature' => 0.7,
        ]);

        return $response->choices[0]->message->content;
    }

    private function getToneGuideline(float $rating): string
    {
        if ($rating >= 4.5) {
            return "Exprime ta gratitude avec enthousiasme";
        } elseif ($rating >= 3.5) {
            return "Remercie et mentionne l'amélioration continue";
        } else {
            return "Présente des excuses sincères et propose une solution";
        }
    }

    public function suggestMultipleResponses(Review $review, int $count = 3): array
    {
        $responses = [];

        for ($i = 0; $i < $count; $i++) {
            $responses[] = [
                'id' => $i + 1,
                'content' => $this->generateResponse($review),
                'tone' => $this->detectTone(),
            ];
        }

        return $responses;
    }
}
```

**Endpoint API:**
```php
// app/Http/Controllers/Api/ReviewController.php
public function suggestResponse(Review $review)
{
    $this->authorize('manage', $review->business);

    $suggestions = app(ReviewResponseService::class)
        ->suggestMultipleResponses($review, 3);

    return response()->json([
        'suggestions' => $suggestions
    ]);
}

public function autoReply(Review $review, Request $request)
{
    $validated = $request->validate([
        'suggestion_id' => 'sometimes|integer',
        'custom_message' => 'sometimes|string',
    ]);

    if (isset($validated['custom_message'])) {
        $reply = $validated['custom_message'];
    } else {
        $suggestions = app(ReviewResponseService::class)
            ->suggestMultipleResponses($review, 1);
        $reply = $suggestions[0]['content'];
    }

    $review->update([
        'reply' => $reply,
        'replied_at' => now(),
    ]);

    // Dispatch job pour publier sur la plateforme
    dispatch(new SendReviewResponse($review));

    return new ReviewResource($review);
}
```

**Frontend Component:**
```vue
<!-- resources/js/components/Reviews/AIResponseSuggestions.vue -->
<template>
  <div class="space-y-4">
    <button @click="generateSuggestions" class="btn btn-primary">
      ✨ Générer des suggestions de réponse
    </button>

    <div v-if="loading" class="text-center py-8">
      <div class="spinner"></div>
      <p>Génération en cours...</p>
    </div>

    <div v-if="suggestions.length" class="space-y-4">
      <div
        v-for="suggestion in suggestions"
        :key="suggestion.id"
        class="p-4 border rounded-lg hover:border-primary-500 cursor-pointer"
        :class="{ 'border-primary-500 bg-primary-50': selected === suggestion.id }"
        @click="selectSuggestion(suggestion)"
      >
        <div class="flex justify-between items-start mb-2">
          <span class="text-xs font-medium text-gray-500">
            Suggestion {{ suggestion.id }}
          </span>
          <span class="text-xs px-2 py-1 rounded bg-blue-100 text-blue-800">
            {{ suggestion.tone }}
          </span>
        </div>
        <p class="text-gray-700">{{ suggestion.content }}</p>
      </div>

      <div class="flex space-x-2">
        <button @click="useSuggestion" class="btn btn-primary">
          Utiliser cette réponse
        </button>
        <button @click="regenerate" class="btn btn-secondary">
          🔄 Régénérer
        </button>
        <button @click="editManually" class="btn btn-secondary">
          ✏️ Modifier manuellement
        </button>
      </div>
    </div>
  </div>
</template>
```

---

## Phase 2 : CRM & Marketing Automation (Mois 4-6) 📊

### 2.1 CRM Complet

**Nouvelles Tables:**
```php
// Migration
Schema::create('customers', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->string('email')->unique();
    $table->string('phone')->nullable();
    $table->string('first_name');
    $table->string('last_name');
    $table->date('birth_date')->nullable();
    $table->json('preferences')->nullable(); // allergies, dietary
    $table->json('tags')->nullable();
    $table->enum('tier', ['regular', 'vip', 'super_vip'])->default('regular');
    $table->decimal('lifetime_value', 10, 2)->default(0);
    $table->integer('visit_count')->default(0);
    $table->timestamp('last_visit_at')->nullable();
    $table->timestamps();
});

Schema::create('customer_visits', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->foreignUuid('customer_id')->constrained()->cascadeOnDelete();
    $table->foreignUuid('business_id')->constrained()->cascadeOnDelete();
    $table->timestamp('visited_at');
    $table->decimal('amount_spent', 10, 2)->nullable();
    $table->integer('party_size')->default(1);
    $table->json('items_ordered')->nullable();
    $table->integer('satisfaction_score')->nullable();
    $table->timestamps();
});

Schema::create('customer_segments', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->string('name');
    $table->text('description')->nullable();
    $table->json('criteria'); // RFM, tags, behavior
    $table->boolean('auto_update')->default(true);
    $table->timestamps();
});
```

**Modèle Customer:**
```php
// app/Models/Customer.php
class Customer extends Model
{
    use HasUuid;

    protected $casts = [
        'preferences' => 'array',
        'tags' => 'array',
        'birth_date' => 'date',
        'last_visit_at' => 'datetime',
    ];

    // Relationships
    public function visits()
    {
        return $this->hasMany(CustomerVisit::class);
    }

    public function segments()
    {
        return $this->belongsToMany(CustomerSegment::class);
    }

    // Business Logic
    public function calculateLifetimeValue(): float
    {
        return $this->visits()->sum('amount_spent');
    }

    public function getRFMScore(): array
    {
        $recency = $this->last_visit_at?->diffInDays(now()) ?? 999;
        $frequency = $this->visit_count;
        $monetary = $this->lifetime_value;

        return [
            'recency' => $this->scoreRecency($recency),
            'frequency' => $this->scoreFrequency($frequency),
            'monetary' => $this->scoreMonetary($monetary),
            'total' => 0, // Calculate total
        ];
    }

    public function isAtRiskOfChurn(): bool
    {
        if (!$this->last_visit_at) {
            return false;
        }

        $daysSinceLastVisit = $this->last_visit_at->diffInDays(now());
        $averageVisitInterval = $this->calculateAverageVisitInterval();

        // At risk if 2x the average interval has passed
        return $daysSinceLastVisit > ($averageVisitInterval * 2);
    }

    public function promoteToVIP(): void
    {
        if ($this->lifetime_value >= 500 && $this->visit_count >= 10) {
            $this->update(['tier' => 'vip']);

            // Send VIP welcome email
            $this->notify(new VIPWelcomeNotification());
        }
    }
}
```

**Service CRM:**
```php
// app/Services/CRM/CustomerService.php
class CustomerService
{
    public function identifyCustomerFromReview(Review $review): ?Customer
    {
        return Customer::where('email', $review->author_email)
            ->orWhere('name', $review->author_name)
            ->first();
    }

    public function segmentCustomers(Business $business): void
    {
        $customers = $business->customers;

        // Segment VIP
        $vipSegment = CustomerSegment::firstOrCreate([
            'name' => 'VIP',
            'criteria' => [
                'lifetime_value_min' => 500,
                'visit_count_min' => 10,
            ]
        ]);

        // Segment à risque
        $atRiskSegment = CustomerSegment::firstOrCreate([
            'name' => 'At Risk',
            'criteria' => [
                'days_since_last_visit_min' => 60,
            ]
        ]);

        foreach ($customers as $customer) {
            // Auto-assign segments
            if ($customer->lifetime_value >= 500 && $customer->visit_count >= 10) {
                $customer->segments()->syncWithoutDetaching([$vipSegment->id]);
            }

            if ($customer->isAtRiskOfChurn()) {
                $customer->segments()->syncWithoutDetaching([$atRiskSegment->id]);
            }
        }
    }

    public function runWinBackCampaign(CustomerSegment $segment): void
    {
        $customers = $segment->customers;

        foreach ($customers as $customer) {
            // Send personalized win-back offer
            dispatch(new SendWinBackEmail($customer));
        }
    }
}
```

---

### 2.2 Email Marketing Automation

**Installation:**
```bash
composer require symfony/mailer
composer require laravel/mailgun-driver
composer require sendgrid/sendgrid
```

**Configuration:**
```php
// config/services.php
'mailgun' => [
    'domain' => env('MAILGUN_DOMAIN'),
    'secret' => env('MAILGUN_SECRET'),
    'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
],
```

**Campaign Models:**
```php
Schema::create('email_campaigns', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->foreignUuid('business_id')->constrained()->cascadeOnDelete();
    $table->string('name');
    $table->string('subject');
    $table->text('content');
    $table->enum('status', ['draft', 'scheduled', 'sending', 'sent'])->default('draft');
    $table->timestamp('scheduled_for')->nullable();
    $table->json('target_segments')->nullable();
    $table->integer('sent_count')->default(0);
    $table->integer('opened_count')->default(0);
    $table->integer('clicked_count')->default(0);
    $table->timestamps();
});
```

**Email Templates:**
```php
// app/Mail/Campaigns/WinBackEmail.php
class WinBackEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Customer $customer,
        public Business $business
    ) {}

    public function build()
    {
        $daysSinceLastVisit = $this->customer->last_visit_at->diffInDays(now());

        return $this->subject("On vous a manqué chez {$this->business->name} ! 🎁")
            ->markdown('emails.campaigns.winback', [
                'customer' => $this->customer,
                'business' => $this->business,
                'daysSinceLastVisit' => $daysSinceLastVisit,
                'specialOffer' => $this->generateSpecialOffer(),
            ]);
    }

    private function generateSpecialOffer(): array
    {
        return [
            'type' => 'discount',
            'value' => 20,
            'code' => 'WELCOME_BACK_' . strtoupper(Str::random(6)),
            'expires_at' => now()->addDays(14),
        ];
    }
}
```

**Automation Workflow:**
```php
// app/Services/Marketing/AutomationService.php
class AutomationService
{
    public function setupAutomations(Business $business): void
    {
        // Automation 1: Welcome new customers
        Automation::create([
            'business_id' => $business->id,
            'name' => 'Welcome New Customers',
            'trigger' => 'customer_created',
            'actions' => [
                ['type' => 'send_email', 'template' => 'welcome', 'delay' => 0],
                ['type' => 'send_sms', 'template' => 'welcome', 'delay' => 0],
            ]
        ]);

        // Automation 2: Birthday wishes
        Automation::create([
            'business_id' => $business->id,
            'name' => 'Birthday Campaign',
            'trigger' => 'customer_birthday',
            'actions' => [
                ['type' => 'send_email', 'template' => 'birthday', 'delay' => -7], // 7 days before
                ['type' => 'give_coupon', 'value' => 15, 'delay' => -7],
            ]
        ]);

        // Automation 3: Win-back campaign
        Automation::create([
            'business_id' => $business->id,
            'name' => 'Win Back Inactive',
            'trigger' => 'customer_inactive_60_days',
            'actions' => [
                ['type' => 'send_email', 'template' => 'winback', 'delay' => 0],
                ['type' => 'give_coupon', 'value' => 20, 'delay' => 0],
                ['type' => 'send_sms', 'template' => 'winback_reminder', 'delay' => 7],
            ]
        ]);
    }
}
```

---

## Temps Estimé & Budget

### Phase 1 (IA) : 3 mois
- **Développement:** 40K€
- **API Credits (OpenAI):** 2K€/mois = 6K€
- **Infrastructure ML:** 4K€
- **Total:** ~50K€

### Phase 2 (CRM) : 3 mois
- **Développement:** 35K€
- **Email service (SendGrid/Mailgun):** 500€/mois = 1.5K€
- **SMS service (Twilio):** 1K€/mois = 3K€
- **Total:** ~40K€

### Total Phase 1+2 : 90K€ sur 6 mois

---

## Prochaines Étapes Immédiates

1. ✅ **Commiter le document** (fait)
2. 🔴 **Choix technologique:**
   - OpenAI vs Anthropic Claude vs Mistral AI?
   - SendGrid vs Mailgun vs AWS SES?
   - Self-hosted ML vs API externe?

3. 🔴 **POC (2 semaines):**
   - Intégrer OpenAI pour 1 use case
   - Tester génération contenu
   - Mesurer coûts réels API

4. 🔴 **Architecture:**
   - Microservice Python pour ML?
   - Tout en Laravel + packages?
   - Queue system (Redis + Horizon)

**Prêt à démarrer le développement? 🚀**
