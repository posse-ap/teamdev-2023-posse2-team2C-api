<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Event_coins_deposit_historyTableSeeder extends Seeder
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
                'user_id' => 2,
                'amount' => 400,
                'event_id' => 3,
                'created_at' => '2023-03-10'
            ],
        ];

        foreach($params as $param) 
        {
            DB::table('event_coins_deposit_histories')->insert(($param));
        }
    }
}
