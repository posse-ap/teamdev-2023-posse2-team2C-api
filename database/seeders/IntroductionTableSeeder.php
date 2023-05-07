<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IntroductionTableSeeder extends Seeder
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
                'intro' => 'かれんだよ'
            ],
            [
                'user_id' => 3,
                'intro' => 'みゆきだよ'
            ],
            [
                'user_id' => 4,
                'intro' => 'admin'
            ],
        ];

        foreach($params as $param)
        {
            DB::table('introductions')->insert($param);
        }
    }
}
