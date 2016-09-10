<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;

class AuthApi
{

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //dd($this->auth);
        if ($this->auth->guest())
        {
            return Response::json('没有权限', 401);
        }
        return $next($request);
    }

}
