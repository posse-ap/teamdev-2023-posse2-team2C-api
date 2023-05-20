<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Role;
use App\Models\Introduction;
use App\Models\Rental;
use App\Models\Item;
use App\Models\Like;
use App\Models\Event;
use App\Models\Coins_converting_history;
use App\Models\Rental_coins_deposit_history;
use App\Models\Event_coins_deposit_history;
use App\Models\Event_points_withdraw_history;
use App\Models\Rental_points_withdraw_history;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTime;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'role_id',
        'slack_id',
        'point',
        'coin',
        'created_at',
        'updated_at',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function newUser($user) 
    {
        User::create([
            'name' => $user['name'],
            'email' => $user['email'],
            'role_id' => 1,
            'slack_id' => $user['slack_id'],
            'password' => Hash::make($user['password']),
            'point' => 5000,
            'coin' => 0,
            // 'created_at' => new DateTime('now'),
        ]);
    }

    public static function checkEmail($email)
    {
        return User::where('email', $email)->select('id', 'name', 'role_id', 'slack_id', 'point', 'coin', )->first();
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id')->first()->role;
    }

    public function intro()
    {
        return $this->belongsTo(Introduction::class, 'id' ,'user_id')->first()->intro;
    }

    // 自分が借りた・借りてる
    public function borrow()
    {
        return $this->hasMany(Rental::class, 'user_id', 'id');
    }
    // 自分が貸した・貸してる
    public function lend()
    {
        return $this->hasMany(Rental::class, 'owner_id', 'id');
    }
    // 自分の出品
    public function items()
    {
        return $this->hasMany(Item::class, 'owner_id', 'id');
    }
    // 自分がいいねした
    public function likes()
    {
        return $this->hasMany(Like::class, 'user_id', 'id');
    }

    // 自分が参加予定→point利用履歴(イベントと同じ)
    // 自分の主催
    public function events()
    {
        return $this->hasMany(Event::class, 'owner_id', 'id');
    }

    // 換金履歴
    public function converting()
    {
        return $this->hasMany(Coins_converting_history::class, 'user_id', 'id');
    }
    // coin 獲得履歴(アイテム)
    public function coinsByRental()
    {
        return $this->hasMany(Rental_coins_deposit_history::class, 'user_id', 'id');
    }
    // coin 獲得履歴(イベント)
    public function coinsByEvent()
    {
        return $this->hasMany(Event_coins_deposit_history::class, 'user_id', 'id');
    }

    // point 利用履歴(イベント)
    public function eventPoint(){
        return $this->hasMany(Event_points_withdraw_history::class, 'user_id', 'id');
    }
    // point 利用履歴(レンタル)
    public function rentalPoint(){
        return $this->hasMany(Rental_points_withdraw_history::class, 'user_id', 'id');
    }
}
