<?php
declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\User\CreateContent;
use App\Services\User\GetContent;
use App\Services\User\UpdateContent;
use App\Services\User\DeleteContent;
use App\Http\Requests\StoreContentRequest;
use App\Http\Requests\DeleteContentRequest;
use App\Http\Requests\UpdateContentRequest;
use Illuminate\Support\Facades\Auth;

class ContentController extends Controller
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
     * プロフィールのコンテンツの取得
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(int $id): JsonResponse
    {
        $service = new GetContent($id);
        $contents = $service->index();
        return response()->json($contents);
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
     * プロフィールの新たなコンテンツの作成
     *
     * @param  \App\Http\Requests\StoreContentRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreContentRequest $request):JsonResponse
    {
        $sectionId = $request->input('sectionId');
        $type = $request->input('type');
        $substance = $request->input('substance');

        $service = new CreateContent();
        $result = $service->create($this->auth->id, $sectionId, $type, $substance);
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
     * プロフィールのコンテンツの更新
     *
     * @param  \App\Http\Requests\UpdateContentRequest $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateContentRequest $request, int $id)
    {
        $sectionId = $request->input('sectionId');
        $contentId = $id;
        $substance = $request->input('substance');

        $service = new UpdateContent();
        $result = $service->update($this->auth->id, $sectionId, $contentId, $substance);
        if(!is_null($result)) {
            return response()->json($result);
        }
        return response()->json('Error');
    }

    /**
     * プロフィールのコンテンツの削除
     *
     * @param  \App\Http\Requests\DeleteContentRequest $request
     * @param  int                                     $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteContentRequest $request, int $id)
    {
        $sectionId = $request->input('sectionId');
        $contentId = $id;

        $service = new DeleteContent();
        $service->remove($this->auth->id, $sectionId, $contentId);
    }
}
