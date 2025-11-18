# CAHIER DES CHARGES FONCTIONNEL DÉTAILLÉ
## Plateforme de Marketing Digital pour Restaurants - Type Malou.io

**Version:** 1.0  
**Date:** 18 Novembre 2025  
**Document:** Partie 1/3 - Introduction et Modules Principaux

---

## SOMMAIRE COMPLET

### PARTIE 1 (Ce document)
1. Présentation Générale
2. Contexte et Objectifs  
3. Périmètre Fonctionnel
4. Module 1: Référencement Local (SEO)
5. Module 2: E-Réputation
6. Module 3: Community Management

### PARTIE 2
7. Module 4: Messagerie Unifiée
8. Module 5: Présence Management
9. Module 6: Analytics & Reporting

### PARTIE 3
10. Module 7: Intelligence Artificielle
11. Module 8: Intégrations
12. Module 9: Administration
13. Architecture Technique
14. Sécurité et Conformité
15. Roadmap et Budget

---

## 1. PRÉSENTATION GÉNÉRALE DU PROJET

### 1.1 Nom du Projet
**RestauBoost** - Plateforme SaaS de Marketing Digital pour Restaurants

### 1.2 Vision
Créer une plateforme tout-en-un permettant aux restaurateurs de gérer leur présence digitale, d'optimiser leur visibilité en ligne et d'augmenter leur chiffre d'affaires grâce à l'automatisation et l'intelligence artificielle, sur le modèle de Malou.io.

### 1.3 Positionnement
Solution B2B SaaS destinée aux:
- Restaurants indépendants
- Chaînes de restaurants (3-50 établissements)
- Groupes de restauration (>50 établissements)
- Franchises dans le secteur CHR (Cafés, Hôtels, Restaurants)

### 1.4 Proposition de Valeur

#### Pour les Restaurateurs
- **Gain de temps:** 28 heures économisées par mois en moyenne
- **Visibilité accrue:** +350% de personnes touchées en ligne
- **Augmentation du CA:** +18% de clients en moyenne
- **Centralisation:** Un seul outil au lieu de 10+ plateformes

#### Pour les Groupes
- **Cohérence de marque:** Gestion centralisée multi-établissements
- **Efficacité opérationnelle:** Automatisation et workflows
- **Data & Insights:** Analytics consolidés et benchmarking
- **Scalabilité:** Solution adaptée de 1 à 1000+ restaurants

---

## 2. CONTEXTE ET OBJECTIFS

### 2.1 Analyse du Marché

#### 2.1.1 Taille du Marché
- **Europe:** 1.8M de restaurants (source: Eurostat 2024)
- **Cible prioritaire:** France (145K), Italie (200K), Espagne (180K)
- **Taux de pénétration digital:** 35% ont un outil marketing digital
- **Marché addressable:** 650K restaurants potentiels

#### 2.1.2 Problématiques Secteur
- 85% des consommateurs cherchent un restaurant en ligne avant de réserver
- 89% lisent les avis clients avant de choisir
- Temps moyen consacré au marketing digital: 15h/semaine
- Complexité: moyenne 8 outils différents utilisés
- Manque de compétences: 67% des restaurateurs se sentent dépassés

### 2.2 Objectifs Business

#### 2.2.1 Objectifs Clients (Bénéfices)
- Réduire le temps marketing de 70%
- Augmenter la visibilité en ligne de 350%
- Accroître le nombre de clients de 18% minimum
- Améliorer la note moyenne de 0.5 à 1 étoile
- Multiplier les avis positifs par 10
- Générer un ROI de 300% minimum

#### 2.2.2 Objectifs Plateforme (Business)
- **Année 1:** 500 restaurants actifs, ARR 600K€
- **Année 2:** 1200 restaurants actifs, ARR 1.8M€
- **Année 3:** 2000 restaurants actifs, ARR 3.5M€
- **Taux de rétention:** >85% annuel
- **NPS (Net Promoter Score):** >50
- **Churn rate:** <5% mensuel

### 2.3 KPIs de Succès

#### Pour les Clients
- Note moyenne Google: passage de 4.0 à 4.5+ en 6 mois
- Nombre d'avis: +200% en 1 an
- Followers réseaux sociaux: +150% en 1 an
- Taux d'engagement: maintien au-dessus de 3%
- Temps de réponse messages: <2h
- Visibilité locale: top 3 Google Maps dans 80% des cas

#### Pour la Plateforme
- CAC (Coût d'Acquisition Client): <500€
- LTV (Lifetime Value): >3000€
- LTV/CAC ratio: >6
- Time to Value: <30 jours (premier résultat visible)
- Taux d'adoption fonctionnalités: >60%
- Support satisfaction: >90%

---

## 3. PÉRIMÈTRE FONCTIONNEL

### 3.1 Modules Principaux

```
RestauBoost Platform
│
├── 1. RÉFÉRENCEMENT LOCAL (SEO)
│   ├── Optimisation Google Business Profile
│   ├── Gestion des mots-clés
│   ├── Audit SEO
│   └── Citations et annuaires
│
├── 2. E-RÉPUTATION
│   ├── Centralisation des avis
│   ├── Réponses automatiques (IA)
│   ├── Génération d'avis
│   └── Analyse de sentiment
│
├── 3. COMMUNITY MANAGEMENT
│   ├── Publication multi-plateformes
│   ├── Création de contenu (IA)
│   ├── Planification
│   └── Engagement et interactions
│
├── 4. MESSAGERIE UNIFIÉE
│   ├── Boîte de réception centralisée
│   ├── Chatbot intelligent
│   ├── Gestion d'équipe
│   └── Automatisation
│
├── 5. PRÉSENCE MANAGEMENT
│   ├── Profil unique multi-plateformes
│   ├── Gestion des menus
│   ├── Bibliothèque photos
│   └── Horaires et fermetures
│
├── 6. ANALYTICS & REPORTING
│   ├── Dashboard global
│   ├── Analytics par module
│   ├── ROI et attribution
│   └── Rapports personnalisés
│
├── 7. INTELLIGENCE ARTIFICIELLE
│   ├── Génération de contenu
│   ├── Analyse et insights
│   ├── Optimisation automatique
│   └── Prédictions
│
├── 8. INTÉGRATIONS
│   ├── Réseaux sociaux (FB, IG, TikTok, LinkedIn)
│   ├── Plateformes avis (Google, TripAdvisor, Yelp)
│   ├── Réservation (TheFork, OpenTable)
│   ├── Livraison (Uber Eats, Deliveroo)
│   └── CRM, PMS, Analytics externes
│
└── 9. ADMINISTRATION
    ├── Gestion utilisateurs
    ├── Permissions et rôles
    ├── Facturation
    ├── Support
    └── Paramètres

```

### 3.2 Fonctionnalités Transversales

- **Authentification:** Multi-facteurs (MFA), SSO, OAuth
- **Notifications:** Temps réel (push, email, SMS, in-app)
- **API:** REST complète, webhooks, rate limiting
- **Multi-device:** Web responsive, apps mobiles iOS/Android
- **Multi-langue:** FR, EN, ES, IT, AR (extensible)
- **Multi-établissement:** Gestion centralisée de 1 à 1000+ restaurants
- **White-label:** Personnalisation possible pour revendeurs

---

## 4. MODULE 1: RÉFÉRENCEMENT LOCAL (SEO)

### 4.1 Objectifs du Module
Maximiser la visibilité des restaurants sur les moteurs de recherche locaux (Google, Bing, Apple Maps) pour apparaître dans les premiers résultats lors des recherches géolocalisées et augmenter le trafic qualifié.

### 4.2 Enjeux Business
- 85% des recherches de restaurants commencent par Google
- Les 3 premiers résultats captent 75% des clics
- Un bon référencement local peut augmenter le trafic en magasin de 30-50%
- Impact direct sur les réservations et le CA

### 4.3 Fonctionnalités Détaillées

#### 4.3.1 Gestion Google Business Profile

**F-SEO-001: Connexion et Synchronisation GMB**

*Description:* Liaison automatique avec le profil Google Business existant

*Workflow:*
1. Recherche du restaurant par nom/adresse
2. Vérification propriétaire via Google OAuth
3. Import des données existantes
4. Synchronisation bidirectionnelle continue
5. Notifications des modifications externes

*Données techniques:*
- API: Google My Business API v4.9
- Fréquence sync: temps réel + vérification horaire
- Gestion erreurs: retry automatique 3x, puis alerte
- Permissions requises: manage_business_information

*Règles:*
- RG-SEO-001: Un seul compte GMB par établissement
- RG-SEO-002: Vérification propriétaire obligatoire (pas de hijack)
- RG-SEO-003: Backup quotidien des données GMB

**F-SEO-002: Édition Complète du Profil GMB**

*Description:* Interface d'édition centralisée de toutes les informations

*Champs gérés:*

```json
{
  "basic_info": {
    "business_name": "string (max 100 char)",
    "categories": ["primary", "secondary1", "secondary2"],
    "description": "string (max 750 char)",
    "phone": "format international",
    "website": "URL validée",
    "booking_url": "URL optionnelle"
  },
  "address": {
    "street_address": "string",
    "locality": "ville",
    "postal_code": "string",
    "country": "ISO 3166-1"
  },
  "hours": {
    "regular": [
      {"day": "MONDAY", "open": "12:00", "close": "14:30"},
      {"day": "MONDAY", "open": "19:00", "close": "22:30"}
    ],
    "special": [
      {"date": "2025-12-25", "closed": true, "reason": "Noël"}
    ]
  },
  "attributes": [
    "has_wifi",
    "has_outdoor_seating",
    "wheelchair_accessible",
    "accepts_credit_cards",
    "pets_allowed"
  ],
  "service_options": {
    "dine_in": true,
    "takeout": true,
    "delivery": false,
    "no_contact_delivery": false
  }
}
```

*Interface:*
- Formulaire multi-étapes (wizard)
- Validation temps réel
- Prévisualisation Google
- Auto-complétion adresse (Google Places)
- Suggestions IA pour description

*Validation:*
- Vérification format téléphone international
- Test validité URL
- Cohérence horaires (close > open)
- Catégories dans taxonomie Google valide

**F-SEO-003: Optimisation des Mots-Clés**

*Description:* Système intelligent de suggestion et suivi de mots-clés

*Processus:*

```
1. Analyse Initiale
   ├── Extraction mots-clés actuels (description, avis, posts)
   ├── Analyse concurrence locale (5 concurrents)
   └── Recherche opportunités (Google Keyword Planner API)

2. Scoring des Mots-Clés
   Pour chaque mot-clé:
   Score = (Volume_Recherche × 0.3) + 
           (Pertinence_Métier × 0.4) + 
           (Facilité_Ranking × 0.3)
   
   Où:
   - Volume_Recherche: 0-100 (normalisé log)
   - Pertinence_Métier: 0-100 (ML classifier)
   - Facilité_Ranking: 100 - Difficulté_Concurrence

3. Recommandations
   ├── Top 10 mots-clés prioritaires
   ├── Mots-clés longue traîne (opportunités)
   └── Mots-clés à éviter (trop compétitifs)

4. Intégration
   ├── Suggestion d'intégration dans description
   ├── Proposition de posts GMB
   └── Optimisation tags photos
```

*Interface Dashboard:*
```
┌────────────────────────────────────────────────────┐
│ Mots-Clés Suivis (12)               [+ Ajouter]   │
├────────────────────────────────────────────────────┤
│ restaurant italien paris 11e                       │
│ Position: #3 (↑2)  Volume: 1200/mois  Diff: 65/100│
│ [📈 Voir Evolution] [🎯 Optimiser]                 │
├────────────────────────────────────────────────────┤
│ pizza napolitaine belleville                       │
│ Position: #5 (↓1)  Volume: 800/mois   Diff: 45/100│
│ ⚠ Action recommandée: Créer post sur pizza        │
├────────────────────────────────────────────────────┤
│ meilleur italien paris                             │
│ Position: #12 (→)  Volume: 3500/mois  Diff: 85/100│
│ 💡 Trop compétitif, cibler variante locale         │
└────────────────────────────────────────────────────┘
```

*Données techniques:*
- Source volume: Google Keyword Planner API + estimations
- Update ranking: quotidien (via serp tracking)
- Historique: 12 mois glissants
- Alertes: changement position >3 places

**F-SEO-004: Posts Google My Business**

*Description:* Création et gestion de posts promotionnels sur GMB

*Types de posts supportés:*

1. **Nouveauté** (What's New)
   - Nouveau plat, nouveau menu, nouvelle carte
   - Durée: 7 jours
   - CTA: "Commander", "Réserver", "En savoir plus"

2. **Événement** (Event)
   - Soirée à thème, concert, privatisation
   - Dates début/fin obligatoires
   - CTA: "Réserver", "S'inscrire", "Billets"

3. **Offre** (Offer)
   - Promotion, réduction, menu spécial
   - Code promo optionnel
   - Dates validité
   - CTA: "Profiter", "Réserver"

4. **Actualité** (Update)
   - Information générale (horaires, fermeture)
   - Durée: 7 jours
   - CTA: "Appeler", "Itinéraire"

*Workflow de création:*

```python
# Pseudo-code du workflow
def create_gmb_post(type, content):
    # 1. Validation
    validate_content(content)
    validate_media(content.images)
    
    # 2. Optimisation IA
    if content.text_needs_improvement:
        content.text = ai_optimize_text(
            original=content.text,
            type=type,
            tone="friendly",
            include_cta=True
        )
    
    # 3. Enrichissement
    content.hashtags = ai_suggest_hashtags(content.text)
    content.keywords = extract_keywords(content.text)
    
    # 4. Planification
    if content.schedule_time:
        schedule_post(content, content.schedule_time)
    else:
        publish_immediately(content)
    
    # 5. Tracking
    create_analytics_tracking(post_id)
```

*Assistant IA de création:*
- Génération du texte à partir de mots-clés
- 3 variantes proposées
- Optimisation longueur (100-1500 caractères optimal)
- Intégration naturelle call-to-action
- Suggestion d'images depuis la bibliothèque

*Templates pré-définis:*
```
- Plat du jour (quotidien)
- Menu du week-end
- Happy hour
- Offre saint-valentin / fête des mères
- Réouverture après congés
- Événement sportif (match, coupe du monde)
```

*Statistiques par post:*
- Vues
- Clics (CTA, photos, téléphone)
- Partages
- Engagement rate
- Comparaison vs moyenne

**F-SEO-005: Questions & Réponses GMB**

*Description:* Gestion proactive et réactive des Q&A

*Fonctionnalités:*

1. **Monitoring Questions**
   - Notification instantanée nouvelle question
   - Classification automatique (Type: Horaires, Menu, Prix, Accès, Autre)
   - Niveau d'urgence (Critique si mentionne "intoxication", "accident")

2. **Réponses Automatiques IA**
   ```python
   def suggest_answer(question):
       # Classification de la question
       question_type = classify_question(question)
       
       # Extraction des informations du profil
       business_data = get_business_profile()
       
       # Génération réponse
       if question_type == "HOURS":
           answer = format_hours_answer(business_data.hours)
       elif question_type == "MENU":
           answer = generate_menu_answer(business_data.menu)
       elif question_type == "PRICE":
           answer = generate_price_answer(business_data.price_level)
       else:
           answer = ai_generate_custom_answer(question, business_data)
       
       return {
           "suggested_answer": answer,
           "confidence": confidence_score,
           "sources": [relevant_data_used]
       }
   ```

3. **Publication Proactive de Q&A**
   - Bibliothèque de FAQ prédéfinies
   - Auto-publication des 10 questions les plus fréquentes
   - Mise à jour automatique si changement (horaires, menu)

*FAQ suggérées par défaut:*
```
1. "Quels sont vos horaires d'ouverture ?"
2. "Acceptez-vous les réservations ?"
3. "Quelle est votre spécialité ?"
4. "Avez-vous des options végétariennes ?"
5. "Y a-t-il un parking à proximité ?"
6. "Acceptez-vous les tickets restaurant ?"
7. "Avez-vous une terrasse ?"
8. "Proposez-vous la livraison à domicile ?"
9. "Quel est le prix moyen d'un repas ?"
10. "Êtes-vous accessible aux personnes à mobilité réduite ?"
```

#### 4.3.2 Audit et Optimisation SEO

**F-SEO-006: Audit SEO Local Complet**

*Description:* Analyse approfondie avec score et recommandations

*Critères audités (100 points total):*

```javascript
const SEO_AUDIT_CRITERIA = {
  profile_completeness: {
    weight: 25,
    checks: {
      basic_info_filled: 5,        // Nom, catégorie, téléphone
      description_optimized: 5,    // >150 char, mots-clés
      hours_complete: 3,           // 7 jours + spéciaux
      attributes_set: 3,           // Min 5 attributs
      website_linked: 2,           // URL valide
      booking_link: 2,             // Si applicable
      photos_count: 5              // Min 10 photos
    }
  },
  
  nap_consistency: {
    weight: 20,
    checks: {
      google_vs_facebook: 5,
      google_vs_website: 5,
      google_vs_tripadvisor: 5,
      format_standardization: 5     // Format uniforme
    }
  },
  
  engagement: {
    weight: 20,
    checks: {
      reviews_count: 5,             // >50 avis
      recent_reviews: 3,            // <30 jours
      review_response_rate: 5,      // >80%
      review_response_time: 3,      // <24h
      qa_answered: 4                // 100% réponses
    }
  },
  
  content_quality: {
    weight: 15,
    checks: {
      description_length: 3,        // 400-750 char optimal
      keywords_present: 4,          // Top KWs intégrés
      photos_quality: 4,            // Résolution, luminosité
      photos_recency: 4             // <90 jours
    }
  },
  
  performance: {
    weight: 20,
    checks: {
      search_impressions: 5,        // Vs benchmark secteur
      profile_views: 5,             // Tendance positive
      actions_taken: 5,             // Calls, directions, clicks
      local_pack_presence: 5        // % requêtes dans top 3
    }
  }
}
```

*Calcul du score:*
```python
def calculate_seo_score(business_data):
    total_score = 0
    detailed_results = {}
    
    for category, criteria in SEO_AUDIT_CRITERIA.items():
        category_score = 0
        category_max = criteria['weight']
        
        for check, points in criteria['checks'].items():
            check_result = evaluate_check(check, business_data)
            earned = points * check_result  # check_result entre 0 et 1
            category_score += earned
            
            detailed_results[check] = {
                'score': earned,
                'max': points,
                'status': get_status(check_result),
                'recommendation': get_recommendation(check, check_result)
            }
        
        total_score += category_score
    
    return {
        'global_score': round(total_score),
        'max_score': 100,
        'rating': get_rating(total_score),  # Excellent/Bon/Moyen/Faible
        'details': detailed_results,
        'priority_actions': get_top_priorities(detailed_results)
    }
```

*Rapport d'audit généré:*

```markdown
# AUDIT SEO LOCAL - La Bella Italia

## Score Global: 87/100 (Très bon)

### Répartition par Catégorie
- ✅ Complétude du Profil: 23/25 (92%)
- ✅ Cohérence NAP: 19/20 (95%)
- ⚠️  Engagement: 14/20 (70%)
- ✅ Qualité Contenu: 14/15 (93%)
- ✅ Performance: 17/20 (85%)

### Actions Prioritaires (3)

1. ⚠️ URGENT - Taux de réponse aux avis
   - Score actuel: 65% (cible: >80%)
   - Impact: -3 points
   - Action: Répondre aux 12 avis en attente
   - Temps estimé: 45 minutes
   - Gain potentiel: +3 points

2. 💡 IMPORTANT - Photos récentes
   - Dernière photo: il y a 67 jours
   - Impact: -2 points
   - Action: Ajouter 5-10 nouvelles photos
   - Temps estimé: 30 minutes
   - Gain potentiel: +2 points

3. 📈 OPPORTUNITÉ - Questions sans réponse
   - 3 questions en attente depuis >7 jours
   - Impact: -1 point
   - Action: Répondre aux questions
   - Temps estimé: 15 minutes
   - Gain potentiel: +1 point

### Comparaison Concurrence
Votre score (87) vs Moyenne locale (76):
- Vous êtes dans le TOP 20% de votre zone
- 3 concurrents devant vous (scores: 91, 89, 88)
- Votre meilleur atout: Qualité contenu (93%)
- Votre point d'amélioration: Engagement (70%)

### Évolution
- Il y a 1 mois: 84/100 (+3 points)
- Tendance: Positive ↗
- Projection à 3 mois: 92/100 (si actions appliquées)
```

**F-SEO-007: Tracking du Positionnement**

*Description:* Suivi quotidien du ranking sur requêtes ciblées

*Données trackées:*

```typescript
interface KeywordTracking {
  keyword: string;
  search_volume: number;          // Estimé mensuel
  difficulty: number;             // 0-100
  
  positions: {
    google_search: {
      organic: number | null;     // Position organique
      local_pack: number | null;  // Position dans le pack local (1-3)
      maps: number | null;        // Position Google Maps
    };
    bing: number | null;
    apple_maps: number | null;
  };
  
  history: {
    date: string;
    position: number;
    impressions: number;
    clicks: number;
  }[];
  
  competitors: {
    name: string;
    position: number;
    distance_km: number;
  }[];
  
  opportunities: {
    type: "featured_snippet" | "people_also_ask" | "local_pack";
    current_holder: string;
    action_suggested: string;
  }[];
}
```

*Algorithme de détection de position:*

```python
def track_keyword_position(keyword, business_location):
    """
    Simule une recherche Google depuis la localisation du restaurant
    """
    # 1. Simulation de recherche géolocalisée
    search_results = google_search_api(
        query=keyword,
        location=business_location,
        result_type="local",
        language="fr"
    )
    
    # 2. Extraction des positions
    position_data = {
        'local_pack': None,
        'organic': None,
        'maps': None
    }
    
    # Vérifier présence dans Local Pack (3 premiers résultats)
    for i, result in enumerate(search_results.local_pack[:3]):
        if result.place_id == business_place_id:
            position_data['local_pack'] = i + 1
            break
    
    # Vérifier présence dans résultats organiques
    for i, result in enumerate(search_results.organic):
        if business_website in result.url:
            position_data['organic'] = i + 1
            break
    
    # Vérifier Google Maps
    maps_results = google_maps_search(keyword, business_location)
    for i, result in enumerate(maps_results[:20]):
        if result.place_id == business_place_id:
            position_data['maps'] = i + 1
            break
    
    # 3. Comparaison avec historique
    previous_position = get_last_position(keyword)
    evolution = calculate_evolution(position_data, previous_position)
    
    # 4. Alertes
    if abs(evolution) >= 3:
        send_alert(keyword, evolution, position_data)
    
    # 5. Sauvegarde
    save_position_history(keyword, position_data, datetime.now())
    
    return {
        'positions': position_data,
        'evolution': evolution,
        'competitors_ahead': get_competitors_ahead(keyword, position_data)
    }
```

*Visualisation:*

```
┌──────────────────────────────────────────────────────────┐
│ restaurant italien paris 11e                              │
│ Position #3 dans Local Pack (↑ 2 places vs hier)         │
├──────────────────────────────────────────────────────────┤
│ Évolution 30 jours:                                      │
│                                                           │
│ Pos                                                       │
│  1│                                  ●─●                  │
│  2│                            ●─●─●                      │
│  3│                      ●─●─●             ●              │
│  4│                ●─●─●                                  │
│  5│          ●─●─●                                        │
│  6│    ●─●─●                                              │
│  7│●─●                                                    │
│   └──────────────────────────────────────────────────────┤
│    01/11              15/11              30/11           │
│                                                           │
│ Concurrents devant vous:                                 │
│ 1. 🥇 Trattoria Romana (à 800m)                          │
│ 2. 🥈 Osteria del Popolo (à 1.2km)                       │
│                                                           │
│ 💡 Pour passer #1:                                       │
│ - Obtenir 15 avis supplémentaires                        │
│ - Ajouter "pasta fresca" dans description                │
│ - Publier 2 posts/semaine sur plats italiens            │
└──────────────────────────────────────────────────────────┘
```

*Alertes configurables:*
- Chute >3 places: Email + SMS immédiat
- Sortie du Local Pack: Email immédiat
- Nouveau concurrent devant: Email quotidien
- Opportunité détectée: Notification in-app

#### 4.3.3 Citations et Présence Multi-Annuaires

**F-SEO-008: Gestion des Citations**

*Description:* Maintien de la cohérence NAP sur tous les annuaires

*Annuaires gérés (30+):*

```yaml
Annuaires_Majeurs:
  - Google Business Profile
  - Facebook Places
  - Apple Maps
  - Bing Places
  - Yelp
  - TripAdvisor
  - Foursquare
  - PagesJaunes
  - Mappy
  - Here Maps
  - Yahoo Local
  - Hotfrog
  - Cylex

Annuaires_Secteur_Restauration:
  - TheFork / LaFourchette
  - OpenTable
  - Zomato
  - Michelin Guide
  - Gault&Millau
  - Petit Futé
  - Routard

Annuaires_Locaux_France:
  - 118712
  - Yelp France
  - Justacoté
  - Solocal

Annuaires_Livraison:
  - Uber Eats (info uniquement)
  - Deliveroo (info uniquement)
  - Just Eat
```

*Processus de scan et vérification:*

```python
def audit_citations():
    """
    Scan de tous les annuaires pour détecter présence et cohérence
    """
    business_name = "La Bella Italia"
    business_address = "123 Rue de la Paix, 75011 Paris"
    business_phone = "+33123456789"
    
    citation_report = {
        'found': [],
        'not_found': [],
        'inconsistent': [],
        'unclaimed': []
    }
    
    for directory in ALL_DIRECTORIES:
        # Recherche dans l'annuaire
        results = directory.search(business_name, business_address)
        
        if not results:
            citation_report['not_found'].append(directory)
            continue
        
        listing = results[0]
        
        # Vérification cohérence NAP
        nap_consistent = True
        inconsistencies = []
        
        if not compare_names(listing.name, business_name):
            nap_consistent = False
            inconsistencies.append(f"Nom: '{listing.name}' vs '{business_name}'")
        
        if not compare_addresses(listing.address, business_address):
            nap_consistent = False
            inconsistencies.append(f"Adresse différente")
        
        if normalize_phone(listing.phone) != normalize_phone(business_phone):
            nap_consistent = False
            inconsistencies.append(f"Tél: {listing.phone} vs {business_phone}")
        
        # Vérification propriétaire
        if not listing.is_claimed:
            citation_report['unclaimed'].append({
                'directory': directory,
                'listing': listing
            })
        
        if nap_consistent:
            citation_report['found'].append(directory)
        else:
            citation_report['inconsistent'].append({
                'directory': directory,
                'listing': listing,
                'issues': inconsistencies
            })
    
    # Calcul du score de citation
    total_directories = len(ALL_DIRECTORIES)
    score = (len(citation_report['found']) / total_directories) * 100
    
    return {
        'score': round(score),
        'report': citation_report,
        'recommendations': generate_citation_recommendations(citation_report)
    }
```

*Actions automatiques proposées:*
1. **Création de profils manquants:**
   - Génération automatique du profil
   - Soumission via API (si disponible)
   - Sinon: instructions manuelles

2. **Correction des incohérences:**
   - Mise à jour via API
   - Pour annuaires sans API: export CSV pour update manuel
   - Historique des corrections

3. **Revendication des profils:**
   - Process de verification automatisé
   - Suivi du statut de revendication
   - Relances automatiques

*Dashboard citations:*
```
┌────────────────────────────────────────────────────┐
│ CITATIONS - Score: 82/100                          │
├────────────────────────────────────────────────────┤
│ ✅ Présent et cohérent: 25 annuaires               │
│ ⚠️  Incohérences détectées: 3 annuaires            │
│ ❌ Absent: 5 annuaires                             │
│ 🔓 Non revendiqué: 2 annuaires                     │
├────────────────────────────────────────────────────┤
│ Incohérences à corriger:                           │
│                                                     │
│ ⚠️ PagesJaunes                                     │
│    Téléphone: 0123456788 (manque un 9)            │
│    [Corriger automatiquement]                      │
│                                                     │
│ ⚠️ Yelp                                            │
│    Horaires: Différents des horaires réels        │
│    [Corriger automatiquement]                      │
│                                                     │
│ ⚠️ Apple Maps                                      │
│    Nom: "Bella Italia" (manque "La")              │
│    [Corriger automatiquement]                      │
├────────────────────────────────────────────────────┤
│ Annuaires à créer (haute priorité):               │
│ • Zomato (impact SEO: Élevé)                       │
│ • Petit Futé (impact: Moyen)                       │
│ • 118712 (impact: Moyen)                           │
│                                                     │
│ [Créer tous les profils manquants]                │
└────────────────────────────────────────────────────┘
```

### 4.4 Données et Algorithmes

#### Score SEO Global

```python
def calculate_global_seo_score(business):
    """
    Calcule le score SEO global de 0 à 100
    """
    
    # Poids des composantes
    WEIGHTS = {
        'profile_completeness': 0.25,
        'engagement': 0.20,
        'reviews': 0.20,
        'citations': 0.15,
        'performance': 0.20
    }
    
    # Calcul des sous-scores
    scores = {}
    
    # 1. Complétude du profil (0-100)
    scores['profile_completeness'] = calculate_profile_completeness(business)
    
    # 2. Engagement (0-100)
    scores['engagement'] = calculate_engagement_score(business)
    
    # 3. Avis (0-100)
    scores['reviews'] = calculate_reviews_score(business)
    
    # 4. Citations (0-100)
    scores['citations'] = calculate_citations_score(business)
    
    # 5. Performance (0-100)
    scores['performance'] = calculate_performance_score(business)
    
    # Score global pondéré
    global_score = sum(scores[key] * WEIGHTS[key] for key in scores)
    
    return {
        'global_score': round(global_score, 1),
        'component_scores': scores,
        'rating': get_rating(global_score),
        'percentile': get_percentile(global_score, business.category, business.location)
    }

def calculate_profile_completeness(business):
    """
    Score de complétude: % de champs remplis pondéré par importance
    """
    fields_status = {
        'name': {'filled': True, 'weight': 10},
        'category': {'filled': True, 'weight': 10},
        'phone': {'filled': True, 'weight': 10},
        'address': {'filled': True, 'weight': 10},
        'website': {'filled': True, 'weight': 5},
        'description': {'filled': len(business.description) > 150, 'weight': 10},
        'hours': {'filled': business.hours_complete(), 'weight': 10},
        'attributes': {'filled': len(business.attributes) >= 5, 'weight': 10},
        'photos': {'filled': len(business.photos) >= 10, 'weight': 15},
        'menu': {'filled': business.has_menu, 'weight': 5},
        'booking_link': {'filled': business.booking_url is not None, 'weight': 5}
    }
    
    total_weight = sum(field['weight'] for field in fields_status.values())
    earned_weight = sum(
        field['weight'] for field in fields_status.values() if field['filled']
    )
    
    return (earned_weight / total_weight) * 100
```

### 4.5 Interfaces Utilisateur

#### Dashboard SEO Principal

*Wireframe:*

```
┌────────────────────────────────────────────────────────────────┐
│ 🔍 RÉFÉRENCEMENT LOCAL                          [🔔5] [👤]     │
├────────────────────────────────────────────────────────────────┤
│ Score SEO: 87/100 [▓▓▓▓▓▓▓▓▓░] Très Bon  ↑ +5 vs mois dernier│
│                                                                 │
│ ┌──────────────┐ ┌──────────────┐ ┌──────────────┐           │
│ │ Position Moy.│ │ Impressions  │ │ Actions      │           │
│ │     #3       │ │   12,500     │ │    2,340     │           │
│ │    ↑ 2       │ │   ↑ 15%      │ │    ↑ 23%     │           │
│ └──────────────┘ └──────────────┘ └──────────────┘           │
│                                                                 │
│ Google Business Profile                                        │
│ ├─ Complétude: 95% [▓▓▓▓▓▓▓▓▓░] ✓                           │
│ ├─ Photos: 45 images (dernière: il y a 5j) ✓                 │
│ ├─ Horaires: À jour ✓                                         │
│ ├─ Q&A: 23 questions, toutes répondues ✓                     │
│ └─ Posts: 12 ce mois (↑ 3 vs mois dernier) ✓                │
│                                                                 │
│ Mots-Clés Suivis (12)                      [Gérer Mots-Clés] │
│ ┌──────────────────────────────────────────────────────────┐ │
│ │ restaurant italien paris 11e  │ #3  ↑2  │ Vol: 1.2K/m  │ │
│ │ pizza napolitaine belleville  │ #5  ↓1  │ Vol: 800/m   │ │
│ │ meilleur italien paris        │ #12 →   │ Vol: 3.5K/m  │ │
│ │ ... 9 autres mots-clés                                   │ │
│ └──────────────────────────────────────────────────────────┘ │
│                                                                 │
│ Citations                                                       │
│ Score: 82/100  │  25 cohérentes  │  3 à corriger              │
│ ⚠️ 3 incohérences détectées  [Voir & Corriger]                │
│                                                                 │
│ Actions Recommandées (3)                                       │
│ 💡 Publier 2 posts cette semaine (engagement ↑)               │
│ 📸 Ajouter 5 nouvelles photos (dernière il y a 67j)           │
│ ⚠️ Corriger incohérences annuaires (PagesJaunes, Yelp, Apple) │
│                                                                 │
│ [📊 Audit Complet] [📈 Rapport SEO] [⚙ Paramètres]           │
└────────────────────────────────────────────────────────────────┘
```

### 4.6 Règles de Gestion et Business Rules

**RG-SEO-001: Fréquence des Mises à Jour**
- Posts GMB: minimum 2 par semaine, maximum 1 par jour
- Photos: ajout recommandé toutes les 2 semaines
- Horaires: mise à jour 24h avant changement
- Description: révision trimestrielle

**RG-SEO-002: Qualité du Contenu**
- Photos: min 1000×1000px, JPG/PNG, <5MB
- Description GMB: 400-750 caractères optimal
- Posts: 100-1500 caractères
- Mots-clés: densité 2-3% dans description

**RG-SEO-003: Notifications et Alertes**
- Position chute >3 places: alerte immédiate (email + SMS)
- Nouveau concurrent top 3: alerte quotidienne
- Incohérence NAP: alerte immédiate
- Score SEO <70: alerte hebdomadaire

**RG-SEO-004: Conformité**
- Respect Google My Business Guidelines
- Pas de keyword stuffing
- Photos authentiques uniquement
- Pas de faux avis sollicités

---

## 5. MODULE 2: E-RÉPUTATION

### 5.1 Objectifs du Module
Gérer de manière centralisée tous les avis clients, répondre rapidement et efficacement, stimuler la génération de nouveaux avis positifs, et améliorer la note moyenne globale.

### 5.2 Enjeux Business
- 89% des consommateurs lisent les avis avant de choisir un restaurant
- Un gain de 0.5 étoile = +18% de réservations en moyenne
- Les restaurants qui répondent aux avis reçoivent 12% d'avis en plus
- Temps moyen passé à gérer les avis: 8h/semaine

### 5.3 Fonctionnalités Détaillées

#### 5.3.1 Centralisation des Avis

**F-REP-001: Agrégation Multi-Sources**

*Description:* Collecte automatique de tous les avis depuis toutes les plateformes

*Plateformes intégrées:*

```yaml
Plateformes_Avis:
  Majeures:
    - google: Google Business Profile (API officielle)
    - tripadvisor: TripAdvisor (API + scraping)
    - facebook: Facebook Pages (Graph API)
    - yelp: Yelp (Fusion API)
  
  Secteur_Restauration:
    - thefork: TheFork / LaFourchette (API partenaire)
    - opentable: OpenTable (API)
    - zomato: Zomato (API)
    - michelin: Michelin Guide (scraping)
  
  Livraison:
    - uber_eats: Uber Eats (API partenaire)
    - deliveroo: Deliveroo (API partenaire)
    - just_eat: Just Eat (API)
  
  Autres:
    - trustpilot: Trustpilot (API)
    - pages_jaunes: PagesJaunes (scraping)
    - foursquare: Foursquare (API)
```

*Architecture de collecte:*

```python
class ReviewAggregator:
    """
    Système de collecte des avis multi-sources
    """
    
    def __init__(self, business_id):
        self.business_id = business_id
        self.sources = self.init_sources()
    
    def init_sources(self):
        """Initialisation des connecteurs par plateforme"""
        return {
            'google': GoogleBusinessConnector(api_key),
            'tripadvisor': TripAdvisorConnector(api_key),
            'facebook': FacebookConnector(access_token),
            'thefork': TheForkConnector(api_key),
            # ... autres sources
        }
    
    async def collect_all_reviews(self):
        """
        Collecte parallèle de tous les avis
        """
        tasks = []
        for platform, connector in self.sources.items():
            task = asyncio.create_task(
                self.collect_from_platform(platform, connector)
            )
            tasks.append(task)
        
        results = await asyncio.gather(*tasks, return_exceptions=True)
        
        all_reviews = []
        errors = []
        
        for platform, result in zip(self.sources.keys(), results):
            if isinstance(result, Exception):
                errors.append({'platform': platform, 'error': str(result)})
                logger.error(f"Error collecting from {platform}: {result}")
            else:
                all_reviews.extend(result)
        
        return {
            'reviews': all_reviews,
            'total_count': len(all_reviews),
            'errors': errors,
            'collected_at': datetime.now()
        }
    
    async def collect_from_platform(self, platform, connector):
        """
        Collecte depuis une plateforme spécifique
        """
        try:
            # Récupération dernière date de sync
            last_sync = get_last_sync_date(self.business_id, platform)
            
            # Collecte incrémentale
            raw_reviews = await connector.fetch_reviews(
                business_id=self.business_id,
                since=last_sync
            )
            
            # Normalisation
            normalized_reviews = [
                self.normalize_review(review, platform)
                for review in raw_reviews
            ]
            
            # Détection des nouveaux avis
            new_reviews = self.filter_new_reviews(
                normalized_reviews, 
                platform
            )
            
            # Sauvegarde
            save_reviews(new_reviews)
            update_last_sync(self.business_id, platform, datetime.now())
            
            # Notifications pour nouveaux avis
            if new_reviews:
                notify_new_reviews(new_reviews)
            
            return new_reviews
            
        except Exception as e:
            logger.error(f"Error collecting from {platform}: {e}")
            raise
    
    def normalize_review(self, raw_review, platform):
        """
        Normalisation des avis au format unifié
        """
        return {
            'id': f"{platform}_{raw_review['id']}",
            'platform': platform,
            'author': {
                'name': raw_review.get('author_name'),
                'avatar_url': raw_review.get('author_photo'),
                'profile_url': raw_review.get('author_url'),
                'review_count': raw_review.get('author_reviews_count', 0)
            },
            'rating': self.normalize_rating(raw_review['rating'], platform),
            'text': raw_review.get('text', ''),
            'language': detect_language(raw_review.get('text', '')),
            'created_at': parse_date(raw_review['date']),
            'updated_at': parse_date(raw_review.get('updated_date')),
            'photos': raw_review.get('photos', []),
            'response': {
                'text': raw_review.get('response_text'),
                'date': parse_date(raw_review.get('response_date')),
                'author': raw_review.get('response_author')
            } if raw_review.get('response_text') else None,
            'url': raw_review['url'],
            'metadata': {
                'verified_purchase': raw_review.get('verified', False),
                'helpful_count': raw_review.get('helpful_votes', 0),
                'flagged': False
            }
        }
    
    def normalize_rating(self, rating, platform):
        """
        Normalise toutes les notes sur une échelle 0-5
        """
        rating_scales = {
            'google': 5,
            'facebook': 5,
            'tripadvisor': 5,
            'yelp': 5,
            'thefork': 10,  # TheFork note sur 10
            'uber_eats': 5,
            'deliveroo': 5
        }
        
        scale = rating_scales.get(platform, 5)
        normalized = (rating / scale) * 5
        
        return round(normalized, 1)
```

*Synchronisation temps réel:*
- Webhooks quand disponibles (Google, Facebook)
- Polling intelligent sinon:
  - Toutes les 15min en heures d'ouverture
  - Toutes les heures en heures creuses
  - Toutes les 4h la nuit

**F-REP-002: Dashboard Avis Unifié**

*Interface de visualisation:*

```
┌──────────────────────────────────────────────────────────────┐
│ 💬 E-RÉPUTATION - Tous les Avis                              │
├──────────────────────────────────────────────────────────────┤
│ Filtres:                                                      │
│ [Toutes plateformes ▼] [Toutes notes ▼] [Ce mois ▼]        │
│ [Tous statuts ▼] [Rechercher...]                            │
├──────────────────────────────────────────────────────────────┤
│ 847 avis au total │ 5 non lus │ 3 non répondus              │
├──────────────────────────────────────────────────────────────┤
│                                                               │
│ ┌─────────────────────────────────────────────────────────┐ │
│ │ 🆕 😊 ⭐⭐⭐⭐⭐ │ Google │ Il y a 2h                      │ │
│ │ Sophie Martin (@sophiefoodie • 127 avis)                │ │
│ │                                                          │ │
│ │ "Excellente soirée ! Les pâtes carbonara étaient       │ │
│ │  absolument divines, cuites à la perfection. Le        │ │
│ │  tiramisu maison était un vrai régal. Service          │ │
│ │  attentionné et rapide. Je recommande vivement!"       │ │
│ │                                                          │ │
│ │ 📊 IA: Très Positif (0.94) │ Thèmes: Nourriture(+),     │ │
│ │     Service(+), Desserts(+)                            │ │
│ │                                                          │ │
│ │ [💡 Réponse IA] [✏️ Répondre] [🏷 Tags] [⋮ Plus]      │ │
│ ├─────────────────────────────────────────────────────────┤ │
│ │ ⚠️ 😟 ⭐⭐ │ TripAdvisor │ Il y a 5h │ URGENT            │ │
│ │ Marc Dupont (@marcd • 45 avis)                         │ │
│ │                                                          │ │
│ │ "Très déçu de notre expérience. Plus d'1h d'attente   │ │
│ │  pour être servi, alors que le restaurant n'était     │ │
│ │  pas plein. Les plats étaient tièdes à l'arrivée.     │ │
│ │  Dommage car la qualité des ingrédients semblait OK." │ │
│ │                                                          │ │
│ │ 📊 IA: Négatif (-0.72) │ Thèmes: Attente(-),           │ │
│ │     Température(-), Service(-)                         │ │
│ │ 🚨 Réponse urgente recommandée (>5h)                   │ │
│ │                                                          │ │
│ │ [💡 Réponse IA] [✏️ Répondre] [🏷 Tags] [⋮ Plus]      │ │
│ ├─────────────────────────────────────────────────────────┤ │
│ │ ✅ 😊 ⭐⭐⭐⭐ │ Facebook │ Hier │ Répondu             │ │
│ │ Emma Laurent                                            │ │
│ │                                                          │ │
│ │ "Bon restaurant dans l'ensemble. Cadre agréable,       │ │
│ │  personnel souriant. Les pizzas sont bonnes mais       │ │
│ │  j'attendais un peu plus pour le prix."                │ │
│ │                                                          │ │
│ │ 📊 IA: Positif (0.58) │ Thèmes: Ambiance(+),           │ │
│ │     Service(+), Prix(=)                                │ │
│ │                                                          │ │
│ │ ✓ Répondu il y a 8h par Marco                          │ │
│ │ "Merci Emma pour votre retour ! Ravis que..."          │ │
│ │                                                          │ │
│ │ [Voir réponse] [🏷 Tags] [⋮ Plus]                      │ │
│ └─────────────────────────────────────────────────────────┘ │
│                                                               │
│ [Charger plus...] (844 autres avis)                         │
└──────────────────────────────────────────────────────────────┘
```

### 5.4 Règles de Gestion

**RG-REP-001: Priorisation**
- Avis 1-2★: Urgent (<6h de réponse)
- Avis 3★: Prioritaire (<12h)
- Avis 4-5★: Normal (<24h)
- Mots-clés critiques ("intoxication", "hygiène"): Immédiat

**RG-REP-002: Qualité Réponses**
- Validation humaine obligatoire pour réponses IA
- Ton respectueux même si avis injuste
- Pas de promesse sans validation direction
- Conservation 3 ans (légal)

---

## 6. MODULE 3: COMMUNITY MANAGEMENT

### 6.1 Objectifs
Animer la présence sur les réseaux sociaux, créer du contenu engageant, augmenter la communauté et l'interaction avec les clients.

### 6.2 Fonctionnalités Principales

**F-CM-001: Publication Multi-Plateformes**
- Connexion FB, IG, TikTok, LinkedIn, Twitter
- Création de posts avec IA
- Planification avancée
- Adaptation automatique par plateforme

**F-CM-002: Création de Contenu IA**
- Génération de textes en <5 secondes
- Templates par type (promo, événement, plat du jour)
- Suggestions d'images
- Hashtags automatiques

**F-CM-003: Calendrier Editorial**
- Vue mensuelle/hebdomadaire
- Drag & drop
- Statuts (brouillon, programmé, publié)
- Détection conflits

*(Suite de ce module dans la Partie 2)*

---

**FIN DE LA PARTIE 1**

> **Note:** Ce document fait partie d'un cahier des charges en 3 parties.  
> Consultez les Parties 2 et 3 pour les modules restants (Messagerie, Présence Management, Analytics, IA, Intégrations, Administration) ainsi que l'architecture technique, la sécurité, la roadmap et le budget.
