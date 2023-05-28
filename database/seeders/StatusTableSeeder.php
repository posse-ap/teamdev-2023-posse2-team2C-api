<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusTableSeeder extends Seeder
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
                'status' => 'applying'
            ],
            [
                'status' => 'rejected'
            ],
            [
                'status' => 'available'
            ],
            [
                'status' => 'borrowed'
            ],
            [
                'status' => 'stopped'
            ],
            [
                'status' => 'planed'
            ],
            [
                'status' => 'canceled'
            ],
            [
                'status' => 'carried_out'
            ],
        ];

        foreach($params as $param)
        {
            DB::table('statuses')->insert($param);
        }
    }
}
