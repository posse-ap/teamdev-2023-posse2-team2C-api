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
        User::with(['rentals' => function ($query) {
            $query->whereNull('deleted_at');
        }, 'rentals.item'])->get()->each(function ($user) {
            $totalCoin = 0;
            $totalPoint = 0;
            foreach ($user->rentals as $rental) {
            // 取得コイン計算と、コイン取得履歴挿入
                if ($rental->owner_id == $user->id) {
                    $totalCoin += $rental->item->price;
                    $rental->insertRentalCoinsDepositHistory();
                }
            // 継続ポイント計算と、ポイント使用履歴挿入
                if ($rental->user_id == $user->id) {
                    $totalPoint += $rental->item->price;
                    $rental->insertRentalPointsWithdrawHistory(2); // $type 2: 継続
                }
            }

            // [持ちcoin/ptリセット]
            $user->update(
                ['coin' => $user->coin + $totalCoin, 'point' => 5000 - $totalPoint]
            );
        });
    }
}
