<?php
declare(strict_types=1);

namespace App\Services\User;

use App\Models\User;
use App\Models\Section;
use App\Models\Content;
use App\Models\Blog;

final class DeleteBlog
{
    public function __construct()
    {
        $this->user = new User();
        $this->section = new Section();
        $this->content = new Content();
        $this->blog = new Blog();
    }

    /**
     * ブログの削除
     *
     * @return int | null
     */
    public function remove(int $userId, int $sectionId, int $blogId)
    {
        $isBlog = $this->blog->where('id', $blogId)->where('user_id', $userId)->where('section_id', $sectionId)->exists();

        if($isBlog) {
            $this->blog->where('id', $blogId)->delete();
            $appUrl = env('APP_URL');
            $blogUrl = $appUrl.'/#/blogs/'.$blogId;
            $content = $this->content->where('substance', $blogUrl)->first();
            return $content->id;
        }
        return null;
    }

    /**
     * ユーザー退会時にそのユーザーの全てのブログの削除
     */
    public function allRemove(int $userId): void
    {
        $isUser = $this->user->where('id', $userId)->exists();
        if($isUser) {
            $this->blog->where('user_id', $userId)->delete();
        }
        return;
    }

    /**
     * セクション削除時にそのセクションの全てのブログの削除
     */
    public function allRemoveInSection(int $userId, int $sectionId): void
    {
        $isSection = $this->section->where('id', $sectionId)->where('user_id', $userId)->exists();
        if($isSection) {
            $this->blog->where('section_id', $sectionId)->delete();
        }
        return;
    }
}
