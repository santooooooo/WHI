<?php
declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\User\CreateSection;
use App\Services\User\UpdateSection;
use App\Services\User\DeleteSection;
use App\Services\User\GetSection;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return 'Hello';
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
     * プロフィールの新たなセクションの作成
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, int $id): JsonResponse
    {
        $userName = $request->input('userName');
        $sectionName = $request->input('sectionName');

        $service = new CreateSection();
        $result = $service->create($id, $userName, $sectionName);
        if(!is_null($result)) {
            return response()->json($result);
        }
        return response()->json('Error');
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
     * プロフィールのセクションの更新
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $userName = $request->input('userName');
        $oldSectionName = $request->input('oldSectionName');
        $newSectionName = $request->input('newSectionName');

        $service = new UpdateSection();
        $result = $service->update($id, $userName, $oldSectionName, $newSectionName);
        if(!is_null($result)) {
            return response()->json($result);
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
