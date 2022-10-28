<?php
declare(strict_types=1);

namespace App\Services\User;

use App\Models\User;
use App\Models\Section;
use App\Models\Content;;

final class DeleteContent
{
    public function __construct()
    {
        $this->user = new User();
        $this->section = new Section();
        $this->content = new Content();
    }

    /**
     * プロフィールのコンテンツの削除
     */
    public function remove(int $userId, int $sectionId, int $contentId): void
    {
        $isUser = $this->user->where('id', $userId)->exists();
        $isSection = $this->section->where('id', $sectionId)->where('user_id', $userId)->exists();
        $isContent = $this->content->where('id', $contentId)->where('user_id', $userId)->where('section_id', $sectionId)->exists();

        if($isUser && $isSection && $isContent) {
            $this->content->where('id', $contentId)->delete();
        }
        return;
    }

    /**
     * ユーザー退会時にそのユーザーの全てのコンテンツの削除
     */
    public function allRemove(int $id, string $name): void
    {
        $isUser = $this->user->where('id', $id)->where('name', $name)->exists();
        if($isUser) {
            $this->content->where('user_id', $id)->delete();
            return;
        }
    }

    /**
     * セクション削除時にそのセクションの全てのコンテンツの削除
     */
    public function allRemoveInSection(int $userId, int $sectionId): void
    {
        $isSection = $this->section->where('id', $sectionId)->where('user_id', $userId)->exists();
        if($isSection) {
            $this->content->where('section_id', $sectionId)->delete();
            return;
        }
    }
}
