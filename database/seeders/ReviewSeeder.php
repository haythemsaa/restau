<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\Review;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $business1 = Business::where('name', 'Brasserie Saint-Germain')->first();
        $business2 = Business::where('name', 'Trattoria Bella Vista')->first();

        // Reviews for Brasserie Saint-Germain
        Review::create([
            'id' => (string) Str::uuid(),
            'business_id' => $business1->id,
            'platform' => 'Google',
            'platform_review_id' => 'google_' . Str::random(10),
            'author_name' => 'Sophie Leblanc',
            'rating' => 5.0,
            'text' => 'Excellente expérience ! La cuisine est délicieuse, le service impeccable. Le bœuf bourguignon était un régal. Je recommande vivement !',
            'sentiment_score' => 0.95,
            'categories' => ['Nourriture+', 'Service+', 'Ambiance+'],
            'created_at' => now()->subDays(15),
        ]);

        Review::create([
            'id' => (string) Str::uuid(),
            'business_id' => $business1->id,
            'platform' => 'TripAdvisor',
            'platform_review_id' => 'ta_' . Str::random(10),
            'author_name' => 'Marc Dubois',
            'rating' => 4.0,
            'text' => 'Très bon restaurant, cadre agréable et plats savoureux. Prix un peu élevés mais la qualité est au rendez-vous.',
            'response_text' => 'Merci Marc pour votre retour ! Nous sommes ravis que vous ayez apprécié votre expérience. Au plaisir de vous revoir bientôt.',
            'response_date' => now()->subDays(9),
            'sentiment_score' => 0.75,
            'categories' => ['Nourriture+', 'Ambiance+', 'Prix-'],
            'created_at' => now()->subDays(10),
        ]);

        Review::create([
            'id' => (string) Str::uuid(),
            'business_id' => $business1->id,
            'platform' => 'Google',
            'platform_review_id' => 'google_' . Str::random(10),
            'author_name' => 'Claire Martin',
            'rating' => 3.0,
            'text' => 'Déçue par le temps d\'attente. Le repas était bon mais nous avons attendu 45 minutes avant d\'être servis.',
            'response_text' => 'Bonjour Claire, nous sommes désolés pour cette attente. Nous prenons note de votre commentaire et travaillons à améliorer notre service. Nous espérons pouvoir vous accueillir à nouveau dans de meilleures conditions.',
            'response_date' => now()->subDays(4),
            'sentiment_score' => 0.30,
            'categories' => ['Service-', 'Nourriture+'],
            'created_at' => now()->subDays(5),
        ]);

        // Reviews for Trattoria Bella Vista
        Review::create([
            'id' => (string) Str::uuid(),
            'business_id' => $business2->id,
            'platform' => 'Google',
            'platform_review_id' => 'google_' . Str::random(10),
            'author_name' => 'Laura Rossi',
            'rating' => 5.0,
            'text' => 'Comme en Italie ! Les pâtes sont faites maison et le tiramisu est à tomber. Personnel très accueillant.',
            'sentiment_score' => 0.98,
            'categories' => ['Nourriture+', 'Service+', 'Authenticité+'],
            'created_at' => now()->subDays(20),
        ]);

        Review::create([
            'id' => (string) Str::uuid(),
            'business_id' => $business2->id,
            'platform' => 'TheFork',
            'platform_review_id' => 'fork_' . Str::random(10),
            'author_name' => 'Antoine Bernard',
            'rating' => 4.5,
            'text' => 'Très bonne adresse italienne à Lyon. Les pizzas sont excellentes avec une pâte fine et croustillante. Bon rapport qualité-prix.',
            'response_text' => 'Grazie mille Antoine ! Nous sommes heureux que nos pizzas vous aient plu. À très bientôt !',
            'response_date' => now()->subDays(11),
            'sentiment_score' => 0.85,
            'categories' => ['Nourriture+', 'Prix+'],
            'created_at' => now()->subDays(12),
        ]);

        Review::create([
            'id' => (string) Str::uuid(),
            'business_id' => $business2->id,
            'platform' => 'Google',
            'platform_review_id' => 'google_' . Str::random(10),
            'author_name' => 'Julie Petit',
            'rating' => 5.0,
            'text' => 'Un vrai voyage en Italie ! Tout était parfait, de l\'entrée au dessert. Mention spéciale pour le risotto aux truffes.',
            'sentiment_score' => 0.99,
            'categories' => ['Nourriture+', 'Expérience+'],
            'created_at' => now()->subDays(3),
        ]);
    }
}
