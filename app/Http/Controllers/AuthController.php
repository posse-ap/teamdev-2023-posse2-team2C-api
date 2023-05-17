<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Session;

use App\Models\User;

class AuthController extends Controller
{
    //
    public function login(Request $request)
    {
        // return $request;
        if (Auth::attempt($request->only(['email', 'password']))) {
            // $request->session()->regenerate();
            $user =  User::getUserFromEmail($request['email']);
            $request->session()->put('user', $user);
            dd($request->session());
            return redirect('http://localhost:3000/UserTop');
        } else {
            // エラーレスポンスを返す
            return response()->json(['message' => 'failed'], 401);
        }
    }

    public function logout(Request $request)
    {
        // ログアウトする
        Auth::logout();
        // レスポンスを返す
        return response()->json(['message' => 'Logged out'], 200);
    }
}
