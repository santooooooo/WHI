<?php
declare(strict_types=1);

namespace App\Services\User;

use App\Models\Blog;
use Carbon\Carbon;

final class GetBlog
{
    public function __construct()
    {
        $this->blog = new Blog();
    }

    /**
     * コンテンツブログの取得
     *
     * @return array{
     * id: int,
     * title: string,
     * text: string,
     * updated: string,
     * } | null
     */
    public function index(int $id)
    {
        $blog = $this->blog->where('id', $id)->first();

        if(is_null($blog)) {
            return null;
        }

        $updated = (new Carbon($blog->updated_at))->toDateTimeString();
        $data = [
        'id' => $blog->id,
        'title' => $blog->title,
        'text' => $blog->text,
        'updated' => substr($updated, 0, 10),
        ];

        return $data;
    }
}
