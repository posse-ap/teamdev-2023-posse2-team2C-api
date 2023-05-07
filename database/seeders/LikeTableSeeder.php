<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LikeTableSeeder extends Seeder
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
                'user_id' => 3,
                'item_id' => 1,
            ],
            [
                'user_id' => 3,
                'item_id' => 2,
            ],
            [
                'user_id' => 3,
                'item_id' => 3,
            ],
            [
                'user_id' => 2,
                'item_id' => 2,
            ],
        ];

        foreach ($params as $param)
        {
            DB::table('likes')->insert($param);
        }
    }
}
