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
                'image_url' => 'https://www.nakool.com/wordpress/wp-content/uploads/2014/06/IMG_9993.jpg',
                'name' => 'ぼうし',
                'owner_id' => 1,
                'price' => 500,
                'detail' => '帽子だよ',
                'status_id' => 3,
                'likes' => 5000,
                'created_at' => '2023-01-01',
            ],
            [
                'image_url' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQecHdTktQ4SLWZaOWwy9fkTC81fQWcnFvmBQ&usqp=CAU',
                'name' => 'ハット',
                'owner_id' => 1,
                'price' => 2500,
                'detail' => 'ハットだよ',
                'status_id' => 4,
                'likes' => 15000,
                'created_at' => '2023-02-01',
            ],
            [
                'image_url' => 'https://gogen-yurai.jp/wp-content/uploads/311735201_675.jpg',
                'name' => '鉛筆',
                'owner_id' => 1,
                'price' => null,
                'detail' => '文具だね',
                'status_id' => 1,
                'created_at' => '2023-03-01',
            ],
            [
                'image_url' => 'https://www.ec-aws.com/img/goods/9/4549917105447_856366e58dd5497fa1e060ccee40e144.jpg',
                'name' => 'シャーペン',
                'owner_id' => 1,
                'price' => 200,
                'detail' => '文具だね',
                'status_id' => 3,
                'created_at' => '2023-03-01',
            ],
            [
                'image_url' => 'https://img.my-best.com/content_section/choice_component/contents/1494d72ae706e5aa7ff1e2c95ab4f2e3.jpeg?ixlib=rails-4.3.1&q=70&lossless=0&w=690&fit=max&s=69e087ff80f7a5a6b397f82b6030d1ff',
                'name' => 'ノート',
                'owner_id' => 2,
                'price' => 500,
                'detail' => 'ノート貸すってどゆこと',
                'status_id' => 4,
                'likes' => 300,
                'created_at' => '2023-04-21',
            ],
            [
                'image_url' => 'https://www.kaunet.com/kaunet/images/goods/option/extra/K3608431.jpg',
                'name' => '消しゴム',
                'owner_id' => 3,
                'price' => 100,
                'detail' => 'すりへるぞ〜',
                'status_id' => 3,
                'likes' => 300,
                'created_at' => '2023-04-21',
            ],
        ];
        foreach($params as $param)
        {
            DB::table('items')->insert($param);
        }
    }
}
