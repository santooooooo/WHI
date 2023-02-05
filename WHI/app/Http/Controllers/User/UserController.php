<?php
declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\User\SignUp;
use App\Services\User\Resign;
use App\Services\User\UpdateUser;
use App\Services\User\WriteProfile;
use App\Services\User\DeleteProfile;
use App\Services\User\DeleteSection;
use App\Services\User\DeleteContent;
use App\Services\User\DeleteBlog;
use App\Services\User\GetUser;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Auth;
use App\Services\User\DestroyUser;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(
            function ($request, $next) {
                $this->auth = Auth::user();
                return $next($request);
            }
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * 新たなユーザーの登録
     *
     * @param  \App\Http\Requests\StoreUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        //
        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');

        $service = new SignUp();
        $id = $service->record($name, $email, $password);
        if($id !== 0) {
            $data = ['id' => $id, 'name' => $name];
            return response()->json($data);
        }
            return response()->json('Error');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $service = new GetUser();
        $userData = $service->getInfo($id);
        return response()->json($userData);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * ユーザー情報の更新
     *
     * @param  \App\Http\Requests\UpdateUserRequest $request
     * @param  int                                  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateUserRequest $request): JsonResponse
    {
        $password = $request->input('password');
        $newName = $request->input('newName');
        $newEmail = $request->input('newEmail');
        $newPasswrod = $request->input('newPassword');

        $service = new UpdateUser();
        $result = $service->update($this->auth->id, $password, $newName, $newEmail, $newPasswrod);
        if($result === 'password wrong') {
            return response()->json($result);

        } elseif($result === 'double email') {
            return response()->json($result);

        } else {
            // ユーザーの認証用のセッションデータの更新
            $this->auth->name =  $result['name'];
            $this->auth->password =  $result['password'];

            return response()->json($result);
        }
    }

    /**
     * ユーザーの退会機能
     *
     * @param  int                     $id
     * @param  Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request): JsonResponse
    {
        $destroyUser = new DestroyUser($this->auth->id, $this->auth->name);
        if($destroyUser->destroy()) {
               // 認証用のセッションデータの削除
               Auth::logout();
               $request->session()->invalidate();
               $request->session()->regenerateToken();
            return response()->json('Success'); 
        } else {
            return response()->json('Error'); 
        }
    }
}
