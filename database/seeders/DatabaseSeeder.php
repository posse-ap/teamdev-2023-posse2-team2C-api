<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call(UserTableSeeder::class);
        $this->call(IntroductionTableSeeder::class);
        $this->call(LikeTableSeeder::class);
        $this->call(RoleTableSeeder::class);
        $this->call(ItemTableSeeder::class);
        $this->call(RentalTableSeeder::class);
        $this->call(StatusTableSeeder::class);
        $this->call(Rental_points_withdraw_historyTableSeeder::class);
        $this->call(EventTableSeeder::class);
        $this->call(Event_participantTableSeeder::class);
        $this->call(Event_coins_deposit_historyTableSeeder::class);
        $this->call(Rental_coins_deposit_historyTableSeeder::class);
        $this->call(Coins_converting_historyTableSeeder::class);
    }
}
