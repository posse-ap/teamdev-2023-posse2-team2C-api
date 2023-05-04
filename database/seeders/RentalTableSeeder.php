<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RentalTableSeeder extends Seeder
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
                'item_id' => 1,
                'user_id' => 3,
                'owner_id' => 1,
                'created_at' => '2023-04-15'
            ],
            [
                'item_id' => 2,
                'user_id' => 3,
                'owner_id' => 1,
                'created_at' => '2023-02-15'
            ],
            [
                'item_id' => 4,
                'user_id' => 3,
                'owner_id' => 1,
                'created_at' => '2023-01-15',
                'deleted_at' => '2023-03-15'
            ],
            [
                'item_id' => 4,
                'user_id' => 1,
                'owner_id' => 2,
                'created_at' => '2023-04-15'
            ],
        ];

        foreach($params as $param)
        {
            DB::table('rentals')->insert($param);
        }
    }
}
