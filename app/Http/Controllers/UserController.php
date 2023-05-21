<?php

namespace App\Http\Controllers;

use App\Models\Rental_coins_deposit_history;
use App\Models\Rental_points_withdraw_history;
use App\Models\User;
use App\Models\Rental;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserController extends Controller
{
    //

    public function show()
    {
        $users = User::get();
        $user_list = [];
        foreach ($users as $index => $user) {
            $user_name = $user->name;
            $listed_items = $user->items()->where('items.status_id', 3)->count();
            $coin_amount = $user->coin;
            $point_amount = $user->point;
            $is_admin = $user->role() === 'admin';

            $user_list[$index] = [
                "id" => $user->id,
                "name" => $user_name,
                "listed_items" => $listed_items,
                "coin_amount" => $coin_amount,
                "point_amount" => $point_amount,
                "is_admin" => $is_admin,
            ];
        }

        return $user_list;
    }

    public function destroy($user_id)
    {
        User::find($user_id)->delete();
    }

    public function updateRole($user_id, $request)
    {
        $newRoleId = $request->is_admin ? 1 : 2;
        User::find($user_id)->update(['role_id' => $newRoleId]);
    }


    public function userInfo()
    {

        // $user = Auth::user();
        $user = User::find(3);
        $lastMonth = Carbon::now()->subMonth()->endOfMonth();
        $history = Rental_points_withdraw_history::where('user_id', $user->id)
            ->whereNull('deleted_at')
            ->where('created_at', '<=', $lastMonth)
            ->sum('amount');
        $deposit = Rental_coins_deposit_history::where('user_id', $user->id)->sum('amount');
        $estimate = Rental::estimateNextMonthCoin($user->id);

        $response = [
            'account' => [
                'name' => $user->name,
            ],
            'point' => [
                'this_month' => $user->point,
                'history' => $history,
            ],
            'coin' => [
                'hold' => $user->coin,
                'deposit' => $deposit,
                'estimate' => $estimate,
            ],
        ];

        return response()->json($response);
    }
}
