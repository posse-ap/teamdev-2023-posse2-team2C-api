<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

use App\Models\User;
use App\Models\Rental;
use App\Models\Like;
use App\Models\Status;

class Item extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $guarded = [];

    public function scopeShownCards($query)
    {
        return $query->where("status_id", 1)->orWhere("status_id", 3)->orWhere("status_id", 4);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id', 'id')->first()->name;
    }

    public function ownerSlackId()
    {
        return $this->belongsTo(User::class, 'owner_id', 'id')->first()->slack_id;
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id', 'id')->first()->status;
    }

    public function history()
    {
        $history = [];
        $rental = $this->hasMany(Rental::class, 'item_id', 'id')->get();
        foreach ($rental as $index => $elem) {
            $name = $elem->belongsTo(User::class, 'user_id', 'id')->first()->name;
            $start = $elem->created_at;
            $start = (new Carbon($start))->toDateString();
            $end = (new Carbon($elem->deleted_at))->toDateString();
            $history[$index] = [
                'name' => $name,
                'start' => $start,
                'end' => $end
            ];
        }
        return $history;
    }

    public function likes()
    {
        $likers = [];
        $likes = $this->hasMany(Like::class, 'item_id', 'id')->get();
        foreach ($likes as $index => $like) {
            $liker = $like->belongsTo(User::class, 'user_id', 'id')->first()->name;
            $likers[$index] = $liker;
        }
        return $likers;
    }

    // status で絞り込み
    public function scopeStatusEqual($query, $status)
    {
        return $query->where('status_id', $status);
    }
}
