<?php
declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\User\CreateContent;
use App\Services\User\CreateBlog;
use App\Services\User\DeleteBlog;
use App\Services\User\DeleteContent;
use App\Services\User\UpdateBlog;
use App\Services\User\GetBlog;
use App\Http\Requests\StoreBlogRequest;
use App\Http\Requests\DeleteBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    /**
     * ユーザーの認証データの取得
     */
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
     * ブログの作成
     *
     * @param  \App\Http\Requests\StoreBlogRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreBlogRequest $request): JsonResponse
    {
        $sectionId = $request->input('sectionId');
        $title = $request->input('title');
        $text = $request->input('text');

        $createBlog = new CreateBlog();
        $substance = $createBlog->create($this->auth->id, $sectionId, $title, $text);
        $type = 'blog';

        $appUrl = env('APP_URL');
        $blogUrl = $appUrl.'/#/blogs/'.$substance;
        $createContent = new CreateContent();
        $createContent->create($this->auth->id, $sectionId, $type, $blogUrl);

        return response()->json($substance);
    }

    /**
     * ブログ情報の表示
     *
     * @param  int $blogId
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $blogId): JsonResponse
    {
        $domain = new GetBlog();
        $blog = $domain->index($blogId);
        return response()->json($blog);

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
     * ブログの更新
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateBlogRequest $request, int $blogId): JsonResponse
    {
        $title = $request->input('title');
        $text = $request->input('text');

        // ブログの更新
        $domain = new UpdateBlog();
        $result = $domain->update($this->auth->id, $blogId, $title, $text);
        if($result) {
            return response()->json('Success');
        }
        return response()->json('Success');
    }

    /**
     * ブログの削除
     *
     * @param int $blogId
     */
    public function destroy(DeleteBlogRequest $request, int $blogId):JsonResponse
    {
        $sectionId = $request->input('sectionId');

        // ブログの削除
        $deleteBlog = new DeleteBlog();
        $contentId = $deleteBlog->remove($this->auth->id, $sectionId, $blogId);

        // ブログが存在しないときの処理
        if(is_null($contentId)) {
            return response()->json('Error');
        } else {
            // ブログのURLが格納されていたコンテンツの削除
            $deleteContent = new DeleteContent();
            $deleteContent->remove($this->auth->id, $sectionId, $contentId);
            return response()->json('Success');
        }
    }
}
