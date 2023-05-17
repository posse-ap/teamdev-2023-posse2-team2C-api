<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class AuthController extends Controller
{
    //
    public function login(Request $request)
    {
        // return $request;
        if (Auth::attempt($request->only(["email", "password"]))) {
            // レスポンスを返す
            return response()->json(['message' => 'success'], 200);
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
