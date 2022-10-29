<?php
declare(strict_types=1);

namespace App\Services\User;

use App\Models\User;
use App\Models\Section;
use App\Models\Content;
use App\Models\Blog;

final class CreateBlog
{
    public function __construct()
    {
        $this->user = new User();
        $this->section = new Section();
        $this->content = new Content();
        $this->blog = new Blog();
    }

    /**
     * コンテンツブログの保存
     *
     * @return string | null
     */
    public function create(int $userId, int $sectionId, string $title, string $text)
    {
        // タイトルとテキストが空の文字列データ出ないかのチェック
        $titleCheck = strlen($title) > 0;
        $textCheck = strlen($text) > 0;

        // ユーザーとそのユーザーのセクションがあるかのチェック
        $isUser = $this->user->where('id', $userId)->exists();
        $isSection = $this->section->where('id', $sectionId)->where('user_id', $userId)->exists();

        if($titleCheck && $textCheck && $isUser && $isSection) {
            $blog = $this->blog->create(
                [
                'user_id' => $userId,
                'section_id' => $sectionId,
                'title' => $title,
                'text' => $text,
                ]
            );
            return $blog->id;
        }
        return null;
    }
}
