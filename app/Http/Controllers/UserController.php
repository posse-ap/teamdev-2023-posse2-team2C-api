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

    public function destroy($user_id){
        User::find($user_id)->delete();
    }

    public function updateRole($user_id, $request){
        $newRoleId = $request->is_admin? 1: 2;
        User::find($user_id)->update(['role_id' => $newRoleId]);
    }
}
