<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return redirect("localhost:3000/auth/login");
        }
    }
    
    //　認証されていない状態なら401エラーを返す
    protected function unauthenticated($request, array $guards)
    {
        abort(response()->json(['error' => 'Unauthenticated.'], 401));
    }
}
