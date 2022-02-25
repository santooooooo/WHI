<?php
declare(strict_types=1);

namespace App\Services\User;

use App\Models\User;
use App\Models\Section;

class UpdateSection
{
    public function __construct()
    {
        $this->user = new User();
        $this->section = new Section();
    }

    /**
     * プロフィールのセクションの更新
     * @return string | null
     */
    public function update(int $id, string $userName, string $oldName, string $newName)
    {
        $isUser = $this->user->where('id', $id)->where('name', $userName)->exists();
        $sectionId = $this->section->where('user_id', $id)->where('name', $oldName)->value('id');
        $nameCheck = strlen($newName) > 0;
        if($isUser && is_int($sectionId) && $nameCheck) {
            $this->section->where('id', $sectionId)->update(
                [
                'name' => $newName
                ]
            );
            return $newName;
        }
        return null;
    }
}
