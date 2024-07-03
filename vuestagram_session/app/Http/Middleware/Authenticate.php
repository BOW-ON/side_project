<?php

namespace App\Http\Middleware;

use App\Exceptions\MyAuthException;
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
        // if (! $request->expectsJson()) {
        //     return route('login');
        // }
        
        // 로그인이 되어 있지 않을때 E22 (미인증 유저입니다) 출력
        throw new MyAuthException('E22');
    }
}
