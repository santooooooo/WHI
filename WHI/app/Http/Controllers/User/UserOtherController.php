<?php
declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\User\Login;
use App\Services\User\GetOGP;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\OgpRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendForgetPasswordUrl;
use App\Services\Auth\SetResetPasswordAuth;
use App\Services\Auth\CheckResetPasswordAuth;

class UserOtherController extends Controller
{
    /**
     * ユーザーのログイン
     *
     * @param  App\Http\Requests\UserLoginRequest $request
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

    /**
     * ユーザーのログイン
     *
     * @param  App\Http\Requests\OgpRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ogp(OgpRequest $request): JsonResponse
    {
        $url = $request->input('url');
        $service = new getOGP();
        $result = $service->OGPInfo($url);
        if(!is_null($result)) {
            return response()->json($result);
        }
        return response()->json('Error');
    }
    /**
     * パスワードの再設定用のURLの送信
     *
     * @param  App\Http\Requests\UserLoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendEmail(Request $request): JsonResponse
    {
        $email = $request->input('email');
        $auth = new SetResetPasswordAuth();
        $identification = $auth->set($email);
        Mail::to($email)->send(new SendForgetPasswordUrl($identification));
        return response()->json('Success');
    }

    /**
     * パスワードの再設定用のIDの確認
     *
     * @param  App\Http\Requests\UserLoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkId(Request $request): JsonResponse
    {
        $id = $request->input('id');
        $checkId = new CheckResetPasswordAuth();
        $email = $checkId->check($id);
        if(!is_null($email)) {
            return response()->json($email);
        }
        return response()->json('Error');
    }

    /**
     * パスワードの再設定用のIDの確認
     *
     * @param  App\Http\Requests\UserLoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(Request $request): JsonResponse
    {
    }
}
