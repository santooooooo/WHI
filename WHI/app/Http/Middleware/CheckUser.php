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
     * リクエスト先が正しいユーザーであるかどうかの確認に使用
     *
     * @param  \Illuminate\Http\Request                                                                          $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $method = $request->method();
        $appUrl = env('APP_URL');

        // テストでのリクエストの場合は何もしない
        $env = env('APP_ENV');
        if($env === 'testing') {
            return $next($request);
        }

        // GETリクエストには何もしない
        if($method !== 'GET') {
            // 新規登録かログインである場合は、認証用のデータの作成と保存を行う
            $url = $request->url();
            if($url === $appUrl.'/login' | $url === $appUrl.'/user') {
                $email = $request->header('user-email');
                $store = new SetAuth();
                $cookie = $store->set($email);
                return $next($request)->cookie('auth', $cookie);
            }

            // POSTまたはPUTリクエストに対してheaderからリクエスト先が正しいかチェック
            $id = $request->header('user-id');
            $name = $request->header('user-name');
            $identification = $request->cookie('auth');
            $check = new CheckAuth();
            $result = $check->check($identification, $id, $name);
            if($result) {
                return $next($request);
            }
            throw new \Exception("wrong user");
        }
        return $next($request);
    }
}
