<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Listing;
use App\Models\Transaction;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123456'),
            'role' => 'admin',
        ]);
        $user = User::factory(10)->create();
        $listings = Listing::factory(10)->create();

        Transaction::factory(10)
            ->state(
                new Sequence(
                    fn ($sequence) => [
                        'user_id' => $user->random()->id,
                        'listing_id' => $listings->random()->id,
                    ]
                )
            )->create();
    }
}
