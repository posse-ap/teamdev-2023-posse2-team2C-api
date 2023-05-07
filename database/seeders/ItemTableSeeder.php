<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemTableSeeder extends Seeder
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
                'name' => 'ぼうし',
                'owner_id' => 1,
                'price' => 500,
                'detail' => '帽子だよ',
                'status_id' => 3,
                'likes' => 5000
            ],
            [
                'name' => 'ハット',
                'owner_id' => 1,
                'price' => 2500,
                'detail' => 'ハットだよ',
                'status_id' => 3,
                'likes' => 15000
            ],
            [
                'name' => '鉛筆',
                'owner_id' => 1,
                'price' => null,
                'detail' => '文具だね',
                'status_id' => 1,
            ],
            [
                'name' => 'ノート',
                'owner_id' => 2,
                'price' => 500,
                'detail' => 'ノート貸すってどゆこと',
                'status_id' => 3,
                'likes' => 300
            ],
        ];
        foreach($params as $param)
        {
            DB::table('items')->insert($param);
        }
    }
}
