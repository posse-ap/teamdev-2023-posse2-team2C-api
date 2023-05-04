<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Coins_converting_historyTableSeeder extends Seeder
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
            [
                'user_id' => 1,
                'amount' => 5000,
                'status' => 10,
                'created_at' => '2022-12-10'
            ],
            [
                'user_id' => 1,
                'amount' => 3000,
                'status' => 9,
                'created_at' => '2022-03-09'
            ],
            [
                'user_id' => 2,
                'amount' => 2000,
                'status' => 10,
                'created_at' => '2022-02-10'
            ],
            [
                'user_id' => 3,
                'amount' => 350000,
                'status' => 10,
                'created_at' => '2022-11-08'
            ],
            [
                'user_id' => 3,
                'amount' => 1000,
                'status' => 9,
                'created_at' => '2022-04-25'
            ],
        ];
    }
}
