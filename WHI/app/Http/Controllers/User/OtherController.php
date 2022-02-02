<?php
declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\User\Login;
use App\Http\Requests\UserLoginRequest;

class OtherController extends Controller
{
    /**
     * 新たなユーザーの登録
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(UserLoginRequest $request): JsonResponse
    {
        $email = $request->input('email');
        $password = $request->input('password');
        $domain = new Login();

        $result = $domain->execute($email, $password);
        if(count($result) > 0) {
            return response()->json($result);
        }
        return response()->json('error');
    }
}
