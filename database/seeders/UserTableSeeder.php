<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
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
                'name' => '金子夏蓮',
                'email' => 'karen@email.com',
                'role_id' => 1,
                'point' => 5000,
                'coin' => 5000,
                'password' => Hash::make('password'),
            ],
            [
                'name' => '寺下渓志郎',
                'email' => 'terashi@email.com',
                'role_id' => 1,
                'point' => 5000,
                'coin' => 5000,
                'password' => Hash::make('password'),
            ],
            [
                'name' => '渡邊美由貴',
                'email' => 'miyuki@email.com',
                'role_id' => 1,
                'point' => 5000,
                'coin' => 5000,
                'password' => Hash::make('password'),
            ],
            [
                'name' => '管理者',
                'email' => 'admin@email.com',
                'role_id' => 2,
                'point' => 5000,
                'coin' => 5000,
                'password' => Hash::make('password'),
            ],
        ];

        foreach ($params as $param) {
            DB::table('users')->insert($param);
        }
    }
}
