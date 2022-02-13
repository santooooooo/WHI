<?php
declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\User\SignUp;
use App\Services\User\Resign;
use App\Services\User\WriteProfile;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\DestroyUserRequest;

class BasicController extends Controller
{
    public function __construct()
    {
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return "Jamboo!";
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
            // 新規ユーザー用のプロフィールDBの作成
            $profile = new WriteProfile();
            $profile->write($id, $name);

            $data = [$id, $name];
            return response()->json($data);
        }
            return response()->json('error');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * ユーザーの退会機能
     *
     * @param  int                                   $id
     * @param  \App\Http\Requests\DestroyUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id, DestroyUserRequest $request): JsonResponse
    {
        $name = $request->input('name');

        $service = new Resign();
        $result = $service->remove($id, $name);
        if($result) {
               return response()->json('Success'); 
        }
        return response()->json('Error'); 
    }
}
