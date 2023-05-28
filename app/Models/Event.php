<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Status;
use App\Models\Event_points_withdraw_history;

use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $guarded = [];

    public function scopeShownCards($query) {
        return $query->where("status_id", 6);
    }

    // 主催者
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id', 'id')->first()->name;
    }

    // status
    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id', 'id')->first()->status;
    }

    // 参加者
    public function participants()
    {
        $participants = [];
        $attendance = $this->hasMany(Event_points_withdraw_history::class, 'event_id', 'id')->get();
        foreach ($attendance as $index => $elem) {
            $price = $elem->price;
            $participant = $elem->belongsTo(User::class, 'user_id', 'id')->first()->name;
            $participants[$index] = [
                'price' => $price,
                'participant' => $participant
            ];
        }
        return $participants;
    }
}
