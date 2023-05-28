<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class Coins_converting_history extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function applier(){
        return $this->belongsTo(User::class, 'user_id', 'id')->first();
    }
}
