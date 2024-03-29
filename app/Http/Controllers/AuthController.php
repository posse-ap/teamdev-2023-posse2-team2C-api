<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Exception;
use Illuminate\Http\JsonResponse;

use Session;

use App\Models\User;
use Laravel\Ui\Presets\React;

class AuthController extends Controller
{
    //
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return response()->json(['name' => Auth::user()->email, 'logged in' => true], 200);
        } else {
            return response()->json(['message' => 'メールアドレスかパスワードが間違っています。', 'logged in' => false], 200);
        }
    }

    public function register(Request $request): JsonResponse
    {
        // return response()->json($request);
        $credentials = $request->validate([
            'name' => 'required',
            'email' => ['required', 'email'],
            'slack_id' => 'required',
            'password' => 'required',
        ]);
        
        if (User::checkEmail($credentials['email']))
        {
            return response()->json('このメールアドレスはすでに使われています');
        } else {
            User::newUser($credentials);
            return response()->json('ユーザー登録が完了しました');
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
