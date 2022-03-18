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
     * @return array | null
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
        'updated' => $updated,
        ];

        return $data;
    }
}
