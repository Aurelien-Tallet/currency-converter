<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Currency;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory(10)->create();
        $this->call(
            [
                CurrencyTableSeeder::class,
                PairTableSeeder::class
            ]
        );
        // create admin user
        User::create([
            'name' => 'admin',
            'email' => 'admin@admin.fr',
            'password' => bcrypt('admin'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
