<?php
declare(strict_types=1);

namespace App\Services\User;

use App\Models\User;
use App\Models\Section;

class CreateSection
{
    public function __construct()
    {
        $this->user = new User();
        $this->section = new Section();
    }

    /**
     * プロフィールのセクションの保存
     */
    public function create(int $id, string $name, string $sectionName): bool
    {
        $isUser = $this->user->where('id', $id)->where('name', $name)->exists();
        $nameCheck = strlen($sectionName) > 0;
        if($isUser && $nameCheck) {
            $this->section->create(
                [
                'user_id' => $id,
                'name' => $sectionName,
                ]
            );
            return true;
        }
        return true;
    }
}
