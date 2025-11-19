# Contributing to RestauBoost 🤝

Merci de votre intérêt pour contribuer à RestauBoost! Ce document contient les guidelines pour contribuer efficacement au projet.

---

## 📋 Table des Matières

1. [Code de Conduite](#code-de-conduite)
2. [Comment Contribuer](#comment-contribuer)
3. [Standards de Code](#standards-de-code)
4. [Workflow Git](#workflow-git)
5. [Tests](#tests)
6. [Documentation](#documentation)
7. [Revue de Code](#revue-de-code)

---

## 🌟 Code de Conduite

### Nos Engagements

- **Respect mutuel** - Traiter tous les contributeurs avec respect
- **Inclusion** - Accueillir les contributions de tous niveaux
- **Constructivité** - Feedback constructif et bienveillant
- **Collaboration** - Travail d'équipe avant tout

### Comportements Inacceptables

- Langage offensant ou discriminatoire
- Harcèlement sous toute forme
- Publication d'informations privées
- Trolling ou commentaires désobligeants

---

## 🚀 Comment Contribuer

### Types de Contributions

**1. Signaler des Bugs 🐛**

```markdown
**Description:**
Description claire du bug

**Étapes pour Reproduire:**
1. Aller sur '...'
2. Cliquer sur '...'
3. Voir l'erreur

**Comportement Attendu:**
Ce qui devrait se passer

**Comportement Actuel:**
Ce qui se passe réellement

**Environnement:**
- OS: [e.g. Ubuntu 22.04]
- PHP Version: [e.g. 8.2.1]
- Laravel Version: [e.g. 10.x]
- Browser: [e.g. Chrome 120]
```

**2. Proposer des Features ✨**

```markdown
**Problème à Résoudre:**
Quel problème cette feature résout-elle?

**Solution Proposée:**
Comment la feature fonctionnerait

**Alternatives Considérées:**
Autres approches envisagées

**Impact:**
Qui bénéficiera de cette feature?
```

**3. Améliorer la Documentation 📚**

- Corriger des typos
- Clarifier des sections
- Ajouter des exemples
- Traduire en d'autres langues

**4. Soumettre du Code 💻**

Voir la section [Workflow Git](#workflow-git) ci-dessous.

---

## 💻 Standards de Code

### PHP (PSR-12)

**Formatting:**
```php
<?php

namespace App\Services;

use App\Models\Customer;
use Illuminate\Support\Collection;

class ExampleService
{
    private CustomerRepository $repository;

    public function __construct(CustomerRepository $repository)
    {
        $this->repository = $repository;
    }

    public function process(Customer $customer): array
    {
        // Clear, descriptive variable names
        $rfmScore = $this->calculateRFMScore($customer);

        // Early returns for clarity
        if ($rfmScore === null) {
            return [];
        }

        return [
            'score' => $rfmScore,
            'tier' => $this->determineTier($rfmScore),
        ];
    }

    private function calculateRFMScore(Customer $customer): ?int
    {
        // Implementation
    }
}
```

**Conventions:**
- **Classes:** PascalCase
- **Methods:** camelCase
- **Variables:** camelCase
- **Constants:** UPPER_SNAKE_CASE
- **Database columns:** snake_case

**Documentation:**
```php
/**
 * Calculate RFM score for a customer
 *
 * @param  Customer  $customer  The customer to analyze
 * @return array{recency: int, frequency: int, monetary: int, total: int}
 */
public function calculateRFMScore(Customer $customer): array
{
    // Implementation
}
```

### JavaScript (ES6+)

**Formatting:**
```javascript
// Clear, descriptive names
const generateContent = async (type, prompt, tone) => {
    // Early validation
    if (!type || !prompt) {
        throw new Error('Type and prompt are required');
    }

    try {
        const response = await fetch('/api/v1/ai/generate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ type, prompt, tone }),
        });

        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Content generation failed:', error);
        throw error;
    }
};

// Export
export { generateContent };
```

**Conventions:**
- **Functions:** camelCase
- **Constants:** UPPER_SNAKE_CASE
- **Components (React):** PascalCase
- Use arrow functions pour callbacks
- Async/await préféré à .then()

### CSS

**Conventions:**
```css
/* BEM Methodology */
.customer-card { }
.customer-card__header { }
.customer-card__title { }
.customer-card--highlighted { }

/* Variables CSS */
:root {
    --primary-color: #6366f1;
    --spacing-base: 16px;
}

/* Mobile-first */
.element {
    /* Mobile styles */
}

@media (min-width: 768px) {
    .element {
        /* Tablet styles */
    }
}
```

### SQL

**Conventions:**
```sql
-- Clear table names (plural)
CREATE TABLE customers (
    id UUID PRIMARY KEY,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Descriptive index names
CREATE INDEX idx_customers_email ON customers(email);
CREATE INDEX idx_customers_tier_active ON customers(tier, at_risk);
```

---

## 🔀 Workflow Git

### 1. Fork & Clone

```bash
# Fork via GitHub interface
# Then clone your fork
git clone https://github.com/YOUR_USERNAME/restau.git
cd restau

# Add upstream
git remote add upstream https://github.com/haythemsaa/restau.git
```

### 2. Créer une Branche

```bash
# Sync with upstream
git fetch upstream
git checkout main
git merge upstream/main

# Create feature branch
git checkout -b feature/amazing-feature

# Or bug fix branch
git checkout -b fix/bug-description
```

**Naming Convention:**
- `feature/` - Nouvelles fonctionnalités
- `fix/` - Corrections de bugs
- `docs/` - Documentation
- `refactor/` - Refactoring
- `test/` - Tests
- `chore/` - Maintenance

### 3. Développer

```bash
# Make your changes
# Add tests
# Update documentation

# Check code style
composer run lint
npm run lint

# Run tests
php artisan test
npm test
```

### 4. Commit

**Format de Commit Message:**
```
type(scope): subject

body

footer
```

**Types:**
- `feat` - Nouvelle fonctionnalité
- `fix` - Correction de bug
- `docs` - Documentation
- `style` - Formatting
- `refactor` - Refactoring
- `test` - Tests
- `chore` - Maintenance

**Exemples:**
```bash
git commit -m "feat(customers): add RFM score calculation"
git commit -m "fix(auth): resolve login redirect issue"
git commit -m "docs(api): update endpoint documentation"
```

**Commit Message Complet:**
```
feat(customers): add RFM score calculation

Implement automatic RFM (Recency, Frequency, Monetary) score
calculation for customer segmentation. Scores are calculated
based on visit history and lifetime value.

- Add RFMAnalysisService
- Add calculate:rfm artisan command
- Update Customer model with score fields
- Add comprehensive tests

Closes #123
```

### 5. Push & Pull Request

```bash
# Push to your fork
git push origin feature/amazing-feature

# Create Pull Request on GitHub
```

**PR Template:**
```markdown
## Description
Brief description of changes

## Type of Change
- [ ] Bug fix
- [ ] New feature
- [ ] Breaking change
- [ ] Documentation update

## Testing
How has this been tested?

- [ ] Unit tests
- [ ] Feature tests
- [ ] Manual testing

## Checklist
- [ ] Code follows project style guidelines
- [ ] Self-review completed
- [ ] Comments added for complex code
- [ ] Documentation updated
- [ ] Tests added/updated
- [ ] All tests passing
- [ ] No new warnings

## Screenshots (if applicable)
```

---

## 🧪 Tests

### Écrire des Tests

**Feature Test Example:**
```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_customer_list(): void
    {
        $user = User::factory()->create();
        Customer::factory()->count(5)->create();

        $response = $this->actingAs($user)
            ->get('/customers');

        $response->assertStatus(200);
        $response->assertViewHas('customers');
    }

    public function test_user_can_create_customer(): void
    {
        $user = User::factory()->create();

        $customerData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '+33612345678',
        ];

        $response = $this->actingAs($user)
            ->post('/customers', $customerData);

        $response->assertRedirect('/customers');
        $this->assertDatabaseHas('customers', ['email' => 'john@example.com']);
    }
}
```

**Unit Test Example:**
```php
<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Customer;
use App\Services\RFMAnalysisService;

class RFMAnalysisServiceTest extends TestCase
{
    public function test_calculates_rfm_score_correctly(): void
    {
        $customer = Customer::factory()->create();
        $service = new RFMAnalysisService();

        $score = $service->calculateRFMScore($customer);

        $this->assertIsArray($score);
        $this->assertArrayHasKey('recency_score', $score);
        $this->assertArrayHasKey('frequency_score', $score);
        $this->assertArrayHasKey('monetary_score', $score);
        $this->assertArrayHasKey('total_score', $score);

        $this->assertGreaterThanOrEqual(0, $score['total_score']);
        $this->assertLessThanOrEqual(15, $score['total_score']);
    }
}
```

### Lancer les Tests

```bash
# All tests
php artisan test

# Specific test
php artisan test --filter CustomerControllerTest

# With coverage
php artisan test --coverage

# Parallel execution
php artisan test --parallel
```

---

## 📚 Documentation

### Code Documentation

**Toujours documenter:**
- Classes publiques
- Méthodes publiques
- Paramètres complexes
- Valeurs de retour
- Exceptions possibles

**PHPDoc Example:**
```php
/**
 * Generate AI content for social media
 *
 * This service uses OpenAI GPT-4 to generate engaging content
 * for various social media platforms based on user input.
 *
 * @param  string  $type  Platform type (facebook, instagram, twitter, email)
 * @param  string  $prompt  Content description/topic
 * @param  string  $tone  Desired tone (professional, friendly, enthusiastic)
 * @return array{content: string, word_count: int, character_count: int}
 *
 * @throws \InvalidArgumentException When invalid type is provided
 * @throws \RuntimeException When API call fails
 */
public function generateContent(string $type, string $prompt, string $tone): array
{
    // Implementation
}
```

### README & Guides

- Mettre à jour README.md pour nouvelles features
- Ajouter exemples d'utilisation
- Documenter breaking changes
- Mettre à jour CHANGELOG.md

---

## 👀 Revue de Code

### En tant que Reviewer

**Points à Vérifier:**
- [ ] Code suit les standards du projet
- [ ] Tests présents et passent
- [ ] Documentation à jour
- [ ] Pas de code commenté inutile
- [ ] Pas de console.log() / dd() oubliés
- [ ] Sécurité (XSS, SQL injection, CSRF)
- [ ] Performance acceptable
- [ ] Accessibilité respectée
- [ ] Responsive design fonctionnel

**Feedback Constructif:**
```markdown
✅ Good:
"Good use of eager loading here! Consider also adding pagination
for better performance with large datasets."

❌ Not Good:
"This is wrong."
```

### En tant qu'Auteur

- **Répondre** à tous les commentaires
- **Expliquer** vos choix si nécessaire
- **Accepter** le feedback avec ouverture
- **Mettre à jour** rapidement après reviews
- **Remercier** les reviewers

---

## 🔧 Environment Setup

### Développement Local

```bash
# 1. Clone & install
git clone https://github.com/YOUR_USERNAME/restau.git
cd restau
composer install
npm install

# 2. Configure
cp .env.example .env
php artisan key:generate

# 3. Database
createdb restauboost
php artisan migrate --seed

# 4. Start
php artisan serve
npm run dev
php artisan queue:work
```

### Avec Docker

```bash
# Start all services
docker-compose up -d

# Run migrations
docker-compose exec app php artisan migrate --seed

# Run tests
docker-compose exec app php artisan test
```

---

## 📝 Checklist Avant de Soumettre

- [ ] Code fonctionne localement
- [ ] Tous les tests passent
- [ ] Code suit les standards
- [ ] Documentation mise à jour
- [ ] Commits bien formatés
- [ ] Branch à jour avec `main`
- [ ] Pas de conflits
- [ ] Testé manuellement
- [ ] Screenshots ajoutés (si UI)
- [ ] CHANGELOG.md mis à jour (si feature)

---

## 🆘 Besoin d'Aide?

- **Documentation:** [README.md](./README.md)
- **API Reference:** [API_DOCUMENTATION.md](./API_DOCUMENTATION.md)
- **Questions:** Ouvrir une [Discussion GitHub](https://github.com/haythemsaa/restau/discussions)
- **Bugs:** Ouvrir une [Issue GitHub](https://github.com/haythemsaa/restau/issues)

---

## 🙏 Remerciements

Merci à tous les contributeurs qui rendent RestauBoost meilleur!

**Contributors:**
<!-- ALL-CONTRIBUTORS-LIST:START -->
<!-- Will be automatically generated -->
<!-- ALL-CONTRIBUTORS-LIST:END -->

---

**Happy Contributing! 🚀**
