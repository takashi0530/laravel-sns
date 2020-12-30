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
        if (! $request->expectsJson()) {
            return route('login');          //ログイン済みでないと見れないページ（authが必須）にアクセスした場合にloginページに飛ばす。ここを変更すればloginページ以外に飛ばせる
        }
    }
}
