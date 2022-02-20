<?php
declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\User\WriteProfile;
use App\Services\User\GetProfile;
use App\Http\Requests\ProfileRequest;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(int $id): JsonResponse
    {
        $service = new GetProfile();
        $profile = $service->getInfo($id);
        return response()->json($profile);
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        return true;
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
     * @param  \App\Http\Requests\ProfileRequest $request
     * @param  int                               $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ProfileRequest $request, int $id): JsonResponse
    {
        $name = $request->input('name');
        $icon = $request->file('icon');
        $career = $request->input('career');
        $title = $request->input('title');
        $text = $request->input('text');
        $email = $request->input('mail');
        $twitter = $request->input('twitter');

        if($icon === null) {
            $icon = null;
        } elseif($icon->extension() !== 'png' && $icon->extension() !== 'jpg') {
            $icon = null;
        }

        $service = new WriteProfile();
        $result = $service->write($id, $name, $icon, $career, $title, $text, $email, $twitter);
        if($result) {
            return response()->json('Success!');
        }
        return response()->json('Error');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
