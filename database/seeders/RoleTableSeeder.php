<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleTableSeeder extends Seeder
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
                'role' => 'user',
            ],
            [
                'role' => 'admin',
            ]
        ];
        foreach($params as $param)
        {
            DB::table('roles')->insert($param);
        }
    }

}
