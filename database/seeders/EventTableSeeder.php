<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventTableSeeder extends Seeder
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
                'name' => 'カルチ体験会',
                'owner_id' => 1,
                'detail' => '何するんだろ',
                'participants' => 2,
                'status_id' => 6,
                'date' => '2023-06-10',
                'created_at' => '2023-03-15'
            ],
            [
                'name' => 'バレー講座',
                'owner_id' => 3,
                'detail' => '初級編',
                'participants' => 3,
                'status_id' => 7,
                'date' => '2023-07-10',
                'created_at' => '2023-04-15',
                'updated_at' => '2023-04-25'
            ],
            [
                'name' => 'ギター弾こうよ',
                'owner_id' => 2,
                'detail' => '弾き語り',
                'participants' => 2,
                'status_id' => 8,
                'date' => '2023-03-10',
                'created_at' => '2023-01-10',
                'updated_at' => '2023-03-10'
            ],
        ];
    }
}
