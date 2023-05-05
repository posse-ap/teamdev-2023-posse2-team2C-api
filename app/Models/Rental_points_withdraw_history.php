<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Rental;

class Rental_points_withdraw_history extends Model
{
    use HasFactory;

    protected $guarded = [];

    // 対応するレンタルを呼び出し
    public function rental () {
        return $this -> belongsTo(Rental::class, 'rental_id', 'id')->first();
    }
}
