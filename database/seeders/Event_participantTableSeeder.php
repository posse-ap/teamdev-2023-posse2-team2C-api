<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Event_participantTableSeeder extends Seeder
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
                'event_id' => 1,
                'user_id' => 2, 
                'price' => 100, 
                'created_at' => '2023-04-15'
            ],
            [
                'event_id' => 1,
                'user_id' => 3, 
                'price' => 500, 
                'created_at' => '2023-03-25'
            ],
            [
                'event_id' => 2,
                'user_id' => 1, 
                'price' => 300, 
                'created_at' => '2023-04-17'
            ],
            [
                'event_id' => 2,
                'user_id' => 2, 
                'price' => 300, 
                'created_at' => '2023-04-20'
            ],
            [
                'event_id' => 2,
                'user_id' => 4, 
                'price' => 500, 
                'created_at' => '2023-04-23'
            ],
            [
                'event_id' => 3,
                'user_id' => 1, 
                'price' => 100, 
                'created_at' => '2023-01-23'
            ],
            [
                'event_id' => 3,
                'user_id' => 3, 
                'price' => 300, 
                'created_at' => '2023-02-23'
            ],
        ];

        foreach($params as $param) 
        {
            DB::table('event_participants')->insert($param);
        }
    }
}
