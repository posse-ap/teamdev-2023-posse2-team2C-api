<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Rental_points_withdraw_historyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $params = 
        [
            // みゆきが帽子借りてる
            [
                'user_id' => 3,
                'amount' => 500,
                'rental_id' => 1,
                'created_at' => '2023-04-15',
                'type' => 1
            ],
            [
                'user_id' => 3,
                'amount' => 500,
                'rental_id' => 1,
                'created_at' => '2023-05-01',
                'type' => 2
            ],

            // みゆきがハット借りてる
            [
                'user_id' => 3,
                'amount' => 2500,
                'rental_id' => 2,
                'created_at' => '2023-02-15',
                'type' => 1
            ],
            [
                'user_id' => 3,
                'amount' => 2500,
                'rental_id' => 2,
                'created_at' => '2023-03-01',
                'type' => 2
            ],
            [
                'user_id' => 3,
                'amount' => 2500,
                'rental_id' => 2,
                'created_at' => '2023-04-01',
                'type' => 2
            ],
            [
                'user_id' => 3,
                'amount' => 2500,
                'rental_id' => 2,
                'created_at' => '2023-05-01',
                'type' => 2
            ],

            // みゆきがノート借りてた
            [
                'user_id' => 3,
                'amount' => 500,
                'rental_id' => 3,
                'created_at' => '2023-01-15',
                'type' => 1
            ],
            [
                'user_id' => 3,
                'amount' => 500,
                'rental_id' => 3,
                'created_at' => '2023-02-01',
                'type' => 2
            ],
            [
                'user_id' => 3,
                'amount' => 500,
                'rental_id' => 3,
                'created_at' => '2023-03-01',
                'deleted_at' => '2023-03-15', // 返却による返金
                'type' => 2
            ],

            // カレンがノート借りてる
            [
                'user_id' => 1,
                'amount' => 500,
                'rental_id' => 4,
                'created_at' => '2023-04-15',
                'type' => 1
            ],
            [
                'user_id' => 1,
                'amount' => 500,
                'rental_id' => 4,
                'created_at' => '2023-05-01',
                'type' => 2
            ],
        ];

        foreach($params as $param) 
        {
            DB::table('rental_points_withdraw_history')->insert($param);
        }
    }
}
