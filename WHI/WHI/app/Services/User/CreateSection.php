<?php
declare(strict_types=1);

namespace App\Services\User;

use App\Models\User;
use App\Models\Section;

final class CreateSection
{
    public function __construct()
    {
        $this->user = new User();
        $this->section = new Section();
    }

    /**
     * プロフィールのセクションの保存
     * @return null | string
     */
    public function create(int $id, string $userName, string $sectionName)
    {
        $isUser = $this->user->where('id', $id)->where('name', $userName)->exists();
        $nameCheck = strlen($sectionName) > 0;
        if($isUser && $nameCheck) {
            $section = $this->section->create(
                [
                'user_id' => $id,
                'name' => $sectionName,
                ]
            );
            return ['id' => $section->id, 'name' => $section->name];
        }
        return null;
    }
}
