<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Item;

class Rental extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function owner() {
        return $this->belongsTo(User::class, 'owner_id', 'id')->first()->name;
    }

    public function item() {
        return $this->belongsTo(Item::class, 'item_id', 'id')->first()->name;
    }
}
