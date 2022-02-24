<?php
declare(strict_types=1);

namespace App\Services\User;

use App\Models\User;
use App\Models\Section;

class DeleteSection
{
    public function __construct()
    {
        $this->user = new User();
        $this->section = new Section();
    }

    /**
     * プロフィールのセクションの削除
     */
    public function remove(int $id, string $name, string $sectionName): bool
    {
        $isUser = $this->user->where('id', $id)->where('name', $name)->exists();
        $sectionId = $this->section->where('user_id', $id)->where('name', $sectionName)->value('id');

        if($isUser && is_int($sectionId)) {
            $this->section->where('id', $sectionId)->delete();
            return true;
        }
        return false;
    }

    /**
     * プロフィールの全てのセクションの削除
     */
    public function allRemove(int $id, string $name): void
    {
        $isUser = $this->user->where('id', $id)->where('name', $name)->exists();
        if($isUser) {
            $this->section->where('user_id', $id)->delete();
            return;
        }
    }
}
