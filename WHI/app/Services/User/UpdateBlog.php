<?php
declare(strict_types=1);

namespace App\Services\User;

use App\Models\User;
use App\Models\Content;
use App\Models\Blog;

final class UpdateBlog
{
    public function __construct()
    {
        $this->user = new User();
        $this->content = new Content();
        $this->blog = new Blog();
    }

    /**
     * コンテンツブログの更新
     *
     */
    public function update(int $userId, int $blogId, string $title, string $text): bool
    {
        // タイトルとテキストが空の文字列データ出ないかのチェック
        $titleCheck = strlen($title) > 0;
        $textCheck = strlen($text) > 0;

        // ユーザーのブログがあるかのチェック
        $isBlog = $this->blog->where('id', $blogId)->where('user_id', $userId)->exists();

        if($titleCheck && $textCheck && $isBlog) {
            $this->blog->where('id', $blogId)->update(
                [
                'title' => $title,
                'text' => $text,
                ]
            );
            return true;
        }
        return false;
    }
}
