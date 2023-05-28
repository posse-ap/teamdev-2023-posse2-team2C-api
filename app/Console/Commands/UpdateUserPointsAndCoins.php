<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class UpdateUserPointsAndCoins extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monthly:UpdateUserPointsAndCoins';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '毎月ユーザーの所持ポイントとコインを更新する';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        User::with(['borrow' => function ($query) {
            $query->whereNull('deleted_at');
        }, 'borrow.item_relation', 'lend' => function ($query) {
            $query->whereNull('deleted_at');
        }, 'lend.item_relation'])->get()->each(function ($user) {
            $totalCoin = 0;
            $totalPoint = 0;
            foreach ($user->lend as $lend) {
                // 取得コイン計算と、コイン取得履歴挿入
                $totalCoin += $lend->item_relation->price;
                $lend->insertRentalCoinsDepositHistory(2);
            };
            foreach ($user->borrow as $borrow) {
                // 継続ポイント計算と、ポイント使用履歴挿入
                $totalPoint += $borrow->item_relation->price;
                $borrow->insertRentalPointsWithdrawHistory(2); // $type 2: 継続
            }

            // 持ちcoin/ptリセット
            $user->update(
                ['coin' => $user->coin + $totalCoin, 'point' => 5000 - $totalPoint]
            );
        });
    }
}
