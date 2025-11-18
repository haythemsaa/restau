<?php

namespace Database\Seeders;

use App\Models\BusinessGroup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BusinessGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BusinessGroup::create([
            'id' => (string) Str::uuid(),
            'name' => 'Les Brasseries Parisiennes',
            'description' => 'Groupe de brasseries traditionnelles situées dans différents arrondissements de Paris',
            'metadata' => [
                'founded' => '2015',
                'total_employees' => 120,
                'headquarters' => 'Paris, France',
            ],
        ]);

        BusinessGroup::create([
            'id' => (string) Str::uuid(),
            'name' => 'Saveurs d\'Italie',
            'description' => 'Chaîne de restaurants italiens authentiques',
            'metadata' => [
                'founded' => '2018',
                'total_employees' => 85,
                'headquarters' => 'Lyon, France',
            ],
        ]);
    }
}
