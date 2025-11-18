<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'id' => (string) Str::uuid(),
            'name' => 'Admin RestauBoost',
            'email' => 'admin@restauboost.com',
            'password' => bcrypt('password'),
            'role' => 'super_admin',
            'mfa_enabled' => false,
            'email_verified_at' => now(),
        ]);

        // Create manager
        User::create([
            'id' => (string) Str::uuid(),
            'name' => 'Jean Dupont',
            'email' => 'jean@restaurant.com',
            'password' => bcrypt('password'),
            'role' => 'manager',
            'mfa_enabled' => false,
            'email_verified_at' => now(),
        ]);

        // Create agents
        User::create([
            'id' => (string) Str::uuid(),
            'name' => 'Marie Martin',
            'email' => 'marie@restaurant.com',
            'password' => bcrypt('password'),
            'role' => 'agent',
            'mfa_enabled' => false,
            'email_verified_at' => now(),
        ]);

        User::create([
            'id' => (string) Str::uuid(),
            'name' => 'Pierre Bernard',
            'email' => 'pierre@restaurant.com',
            'password' => bcrypt('password'),
            'role' => 'agent',
            'mfa_enabled' => false,
            'email_verified_at' => now(),
        ]);
    }
}
