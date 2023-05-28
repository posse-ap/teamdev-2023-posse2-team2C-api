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
                'email' => 'karen@anti-pattern.co.jp',
                'role_id' => 1,
                'slack_id' => 1,
                'point' => 5000,
                'coin' => 5000,
                'password' => Hash::make('password'),
            ],
            [
                'name' => '寺下渓志郎',
                'email' => 'terashi@anti-pattern.co.jp',
                'role_id' => 1,
                'slack_id' => 2,
                'point' => 5000,
                'coin' => 5000,
                'password' => Hash::make('password'),
            ],
            [
                'name' => '渡邊美由貴',
                'email' => 'miyuki@anti-pattern.co.jp',
                'role_id' => 1,
                'slack_id' => 3,
                'point' => 5000,
                'coin' => 5000,
                'password' => Hash::make('password'),
            ],
            [
                'name' => '管理者',
                'email' => 'admin@anti-pattern.co.jp',
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
