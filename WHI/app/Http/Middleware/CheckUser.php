<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\Auth\SetAuth;
use App\Services\Auth\CheckAuth;

class CheckUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request                                                                          $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $method = $request->method();
        $appUrl = env('APP_URL');
        if($method !== 'GET') {
            $url = $request->url();
            if($url === $appUrl.'/login' | $url === $appUrl.'/signup') {
                $email = $request->header('user-email');
                $store = new SetAuth();
                $cookie = $store->set($email);
                return $next($request)->cookie('auth', $cookie);
            }
        }
        return $next($request);
    }
}
