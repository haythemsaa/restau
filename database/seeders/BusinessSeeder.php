<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\BusinessGroup;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BusinessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $group1 = BusinessGroup::where('name', 'Les Brasseries Parisiennes')->first();
        $group2 = BusinessGroup::where('name', 'Saveurs d\'Italie')->first();
        $manager = User::where('email', 'jean@restaurant.com')->first();
        $agent1 = User::where('email', 'marie@restaurant.com')->first();
        $agent2 = User::where('email', 'pierre@restaurant.com')->first();

        // Brasserie Saint-Germain
        $business1 = Business::create([
            'id' => (string) Str::uuid(),
            'group_id' => $group1->id,
            'name' => 'Brasserie Saint-Germain',
            'legal_name' => 'SARL Brasserie Saint-Germain',
            'description' => 'Brasserie traditionnelle française au cœur de Saint-Germain-des-Prés. Cuisine authentique et ambiance chaleureuse.',
            'address' => [
                'street' => '25 Boulevard Saint-Germain',
                'city' => 'Paris',
                'postal_code' => '75005',
                'country' => 'France',
            ],
            'phone' => '+33 1 42 34 56 78',
            'email' => 'contact@brasserie-saintgermain.fr',
            'website' => 'https://brasserie-saintgermain.fr',
            'metadata' => [
                'cuisine_type' => 'Française',
                'price_range' => '€€',
                'capacity' => 80,
                'opening_year' => '2015',
            ],
        ]);

        // Attachusers
        $business1->users()->attach($manager->id, [
            'role' => 'manager',
            'permissions' => ['manage_team', 'view_analytics', 'manage_content'],
        ]);

        $business1->users()->attach($agent1->id, [
            'role' => 'agent_marketing',
            'permissions' => ['manage_content', 'respond_reviews'],
        ]);

        // Trattoria Bella Vista
        $business2 = Business::create([
            'id' => (string) Str::uuid(),
            'group_id' => $group2->id,
            'name' => 'Trattoria Bella Vista',
            'legal_name' => 'SAS Bella Vista',
            'description' => 'Restaurant italien authentique proposant des spécialités de Toscane et Sicile dans une ambiance conviviale.',
            'address' => [
                'street' => '15 Rue de la République',
                'city' => 'Lyon',
                'postal_code' => '69002',
                'country' => 'France',
            ],
            'phone' => '+33 4 78 90 12 34',
            'email' => 'info@bellavista-lyon.fr',
            'website' => 'https://bellavista-lyon.fr',
            'metadata' => [
                'cuisine_type' => 'Italienne',
                'price_range' => '€€',
                'capacity' => 60,
                'opening_year' => '2018',
                'specialties' => ['Pasta', 'Pizza', 'Tiramisu'],
            ],
        ]);

        $business2->users()->attach($manager->id, [
            'role' => 'manager',
            'permissions' => ['manage_team', 'view_analytics', 'manage_content'],
        ]);

        $business2->users()->attach($agent2->id, [
            'role' => 'agent_support',
            'permissions' => ['respond_messages', 'respond_reviews'],
        ]);

        // Bistrot du Marais
        $business3 = Business::create([
            'id' => (string) Str::uuid(),
            'group_id' => $group1->id,
            'name' => 'Bistrot du Marais',
            'legal_name' => 'SARL Bistrot du Marais',
            'description' => 'Bistrot parisien typique proposant une cuisine de saison avec produits frais du marché.',
            'address' => [
                'street' => '42 Rue des Rosiers',
                'city' => 'Paris',
                'postal_code' => '75004',
                'country' => 'France',
            ],
            'phone' => '+33 1 48 87 65 43',
            'email' => 'contact@bistrotdumarais.fr',
            'website' => 'https://bistrotdumarais.fr',
            'metadata' => [
                'cuisine_type' => 'Bistrot',
                'price_range' => '€€',
                'capacity' => 45,
                'opening_year' => '2017',
                'features' => ['Terrasse', 'Menu du jour', 'Vin bio'],
            ],
        ]);

        $business3->users()->attach($manager->id, [
            'role' => 'manager',
            'permissions' => ['manage_team', 'view_analytics', 'manage_content'],
        ]);
    }
}
