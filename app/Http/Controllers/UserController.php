<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;

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
                "name" => $user_name,
                "listed_items" => $listed_items,
                "coin_amount" => $coin_amount,
                "point_amount" => $point_amount,
                "id" => $user->id,
                "is_admin" => $is_admin,
            ];
        }

        return $user_list;
    }
}
