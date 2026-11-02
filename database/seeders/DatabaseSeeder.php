<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * php artisan migrate:fresh --seed
     */
    public function run(): void
    {
        $this->call(TxtSeeder::class);      // counties, towns, populations (database/data/*.txt)
        $this->call(UserSeeder::class);     // User1 (aaa@aaa.hu) + admin (bbb@bbb)
        $this->call(MessageSeeder::class);  // TASK 7 - sample messages for the Messages menu
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
