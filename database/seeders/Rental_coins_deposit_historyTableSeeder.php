<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Rental_coins_deposit_historyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $params = [
            [
                'user_id' => 1,
                'amount' => 500,
                'rental_id' => 1,
                'created_at' => '2023-04-30',
                'type' => 1,
            ],
            [
                'user_id' => 1,
                'amount' => 2500,
                'rental_id' => 2,
                'created_at' => '2023-02-28',
                'type' => 1,
            ],
            [
                'user_id' => 1,
                'amount' => 2500,
                'rental_id' => 2,
                'created_at' => '2023-03-31',
                'type' => 2,
            ],
            [
                'user_id' => 1,
                'amount' => 2500,
                'rental_id' => 2,
                'created_at' => '2023-04-30',
                'type' => 2,
            ],
            [
                'user_id' => 2,
                'amount' => 500,
                'rental_id' => 3,
                'created_at' => '2023-01-31',
                'type' => 1,
            ],
            [
                'user_id' => 2,
                'amount' => 500,
                'rental_id' => 3,
                'created_at' => '2023-02-28',
                'type' => 2,
            ],
            [
                'user_id' => 2,
                'amount' => 500,
                'rental_id' => 4,
                'created_at' => '2023-04-30',
                'type' => 1,
            ],
        ];

        foreach($params as $param)
        {
            DB::table('rental_coins_deposit_histories')->insert($param);
        }
    }
}
