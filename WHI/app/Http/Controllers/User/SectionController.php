<?php
declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\User\CreateSection;
use App\Services\User\UpdateSection;
use App\Services\User\DeleteSection;
use App\Services\User\DeleteContent;
use App\Services\User\DeleteBlog;
use App\Services\User\GetSection;
use App\Http\Requests\SectionRequest;
use App\Http\Requests\UpdateSectionRequest;
use Illuminate\Support\Facades\Auth;

class SectionController extends Controller
{
    // ユーザーの認証データの取得
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
    public function store(SectionRequest $request): JsonResponse
    {
        $sectionName = $request->input('sectionName');

        $service = new CreateSection();
        $result = $service->create($this->auth->id, $this->auth->name, $sectionName);
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
     * @param  int                                     $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateSectionRequest $request): JsonResponse
    {
        $oldSectionName = $request->input('oldSectionName');
        $newSectionName = $request->input('newSectionName');

        $service = new UpdateSection();
        $result = $service->update($this->auth->id, $this->auth->name, $oldSectionName, $newSectionName);
        if(!is_null($result)) {
            return response()->json($result);
        }
        return response()->json('Error');
    }

    /**
     * プロフィールのセクションの削除
     *
     * @param  int                               $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $sectionId = $id;

        // 削除するセクションの全てのブログの削除
        $deleteContent = new DeleteBlog();
        $deleteContent->allRemoveInSection($this->auth->id, $sectionId);

        // 削除するセクションの全てのコンテンツの削除
        $deleteContent = new DeleteContent();
        $deleteContent->allRemoveInSection($this->auth->id, $sectionId);

        // セクションの削除
        $deleteSection = new DeleteSection();
        $deleteSection->remove($this->auth->id, $sectionId);
        return response()->json('Success');
    }
}
