<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\User\SignUp;
use App\Services\User\Resign;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;

class BasicController extends Controller
{
    public function __construct()
    {
        $this->user = new User();
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        //
        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');

        $service = new SignUp($this->user);
        $result = $service->record($name, $email, $password);
        if($result) {
            $data = [$name, $email];
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
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id, Request $request): JsonResponse
    {
        $email = $request->input('email');

        $service = new Resign();
        $result = $service->remove($id, $email);
        if($result) {
               return response()->json('Success'); 
        }
        return response()->json('Error'); 
    }
}
