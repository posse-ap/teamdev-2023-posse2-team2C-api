<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
            // 一斉インサート用の配列を用意
            $coinsDepositData = [];
            $pointsWithdrawData = [];

            foreach ($user->lend as $lend) {
                $coinsDepositData[] = [
                    'user_id' => $lend->owner_id,
                    'amount' => $lend->item_relation->price,
                    'rental_id' => $lend->id,
                    'type' => 2, // $type 2: 継続
                ];
            }

            foreach ($user->borrow as $borrow) {
                $pointsWithdrawData[] = [
                    'user_id' => $borrow->user_id,
                    'amount' => $borrow->item_relation->price,
                    'rental_id' => $borrow->id,
                    'type' => 2, // $type 2: 継続
                ];
            }

            DB::transaction(function () use ($coinsDepositData, $pointsWithdrawData, $user) {
                try {

                    // Bulk insert coin deposit histories
                    if (!empty($coinsDepositData)) {
                        DB::table('rental_coins_deposit_histories')->insert($coinsDepositData);
                    }

                    // Bulk insert points withdraw histories
                    if (!empty($pointsWithdrawData)) {
                        DB::table('rental_points_withdraw_histories')->insert($pointsWithdrawData);
                    }

                    // Calculate and update user coins and points
                    $totalCoin = array_sum(array_column($coinsDepositData, 'amount'));
                    $totalPoint = array_sum(array_column($pointsWithdrawData, 'amount'));

                    $user->update(
                        ['coin' => $user->coin + $totalCoin, 'point' => 5000 - $totalPoint]
                    );
                } catch (\Exception $e) {
                    Log::error("Error processing user {$user->id}: " . $e->getMessage());
                }
            });
        });
    }
}
