<?php

namespace Database\Seeders;

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
        $this->call(GameSeeder::class);
        $this->call(GameResults2026Seeder::class);
        $this->call(GameResults2025Seeder::class);
        //  $this->call(GameResults2024Seeder::class);
        //  $this->call(GameResults2023Seeder::class);

        User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'super@admin.com',
        ]);
        $this->call([
            RolePermissionSeeder::class,
        ]);
    }
}
