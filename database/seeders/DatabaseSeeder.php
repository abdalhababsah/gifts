<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles and reference data first
        $this->call([
            RoleSeeder::class,
            CitySeeder::class,
            DeliveryTimeSlotSeeder::class,
            CategorySeeder::class,
            BrandSeeder::class,
            ProductSeeder::class,
        ]);

        // Create admin user
        $adminRole = Role::where('name', 'admin')->first();
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role_id' => $adminRole->id,
        ]);

        // Create test user
        $userRole = Role::where('name', 'user')->first();
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role_id' => $userRole->id,
        ]);
    }
}
