<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Event;

class Event_points_withdraw_history extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function event() {
        return $this->belongsTo(Event::class, 'event_id', 'id')->first()->name;
    }
}
