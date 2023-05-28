<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Item;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rental extends Model
{
    use HasFactory;
    use SoftDeletes;
    

    protected $guarded = [];

    public function owner() {
        return $this->belongsTo(User::class, 'owner_id', 'id')->first()->name;
    }

    public function item() {
        return $this->belongsTo(Item::class, 'item_id', 'id')->first()->name;
    }

    public function item_relation()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }

    public function rental_points_withdraw_history(): HasMany
    {
        return $this->hasMany(Rental_points_withdraw_history::class);
    }

    public function rental_coins_deposit_history(): HasMany
    {
        return $this->hasMany(rental_coins_deposit_history::class);
    }

    // 貸出中のアイテム取得
    public function scopeGetActiveRentals($query) {
        return $query->whereNull('deleted_at');
    }

    /**
     * レンタル履歴の登録
     *
     * @param int $type 1: 新規, 2: 継続
     * @return void
     */
    public function insertRentalPointsWithdrawHistory($type)
    {
        return $this->rental_points_withdraw_history()->create([
            'user_id' => $this->user_id,
            'amount' => $this->item_relation->price,
            'rental_id' => $this->id,
            'type' => $type,
        ]);
    }

    /**
     * 来月に取得見込みコインの算出
     *
     * @param int $type 1: 新規, 2: 継続
     * @return int
     */
    private function estimateNextMonthCoin($user_id)
    {
        $rentals = $this->where('owner_id', $user_id)->getActiveRentals()->get();
        $totalPrice = 0;
        foreach ($rentals as $rental) {
            $totalPrice += $rental->item_relation->price;
        }
        return $totalPrice;
    }

    /**
     * コイン取得履歴の登録
     *
     * @return void
     */
    public function insertRentalCoinsDepositHistory()
    {
        return $this->rental_coins_deposit_history()->create([
            'user_id' => $this->owner_id,
            'amount' => $this->item_relation->price,
            'rental_id' => $this->id,
        ]);
    }
}
