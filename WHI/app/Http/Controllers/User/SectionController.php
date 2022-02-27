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
use App\Http\Requests\SectionRequest;
use App\Http\Requests\UpdateSectionRequest;

class SectionController extends Controller
{
    /**
     * プロフィールのセクションの取得
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(int $id): JsonResponse
    {
        $service = new GetSection($id);
        $section = $service->index();
        return response()->json($section);
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
     * @param  \App\Http\Requests\SectionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(SectionRequest $request, int $id): JsonResponse
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
     * @param  \App\Http\Requests\UpdateSectionRequest $request
     * @param  int                      $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateSectionRequest $request, int $id): JsonResponse
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
     * プロフィールのセクションの削除
     *
     * @param  int $id
     * @param  \App\Http\Requests\SectionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(SectionRequest $request, int $id)
    {
        $userName = $request->input('userName');
        $sectionName = $request->input('sectionName');

        $service = new DeleteSection();
        $service->remove($id, $userName, $sectionName);
        return response()->json('Success');
    }
}
