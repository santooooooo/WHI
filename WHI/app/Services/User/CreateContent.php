<?php
declare(strict_types=1);

namespace App\Services\User;

use App\Models\User;
use App\Models\Section;
use App\Models\Content;;

class CreateContent
{
    public function __construct()
    {
        $this->user = new User();
        $this->section = new Section();
        $this->content = new Content();
    }

    /**
     * プロフィールのコンテンツの保存
     *
     * @return null | array{
     * id: int,
     * type: string,
     * substance: string,
     * }
     */
    public function create(int $userId, int $sectionId, string $type, string $substance)
    {
        $typeCheck = $type === 'url' | $type === 'text' | $type === 'blog';
        $substanceCheck = strlen($substance) > 0;
        $isUser = $this->user->where('id', $userId)->exists();
        $isSection = $this->section->where('id', $sectionId)->where('user_id', $userId)->exists();

        if($typeCheck && $substanceCheck && $isUser && $isSection) {
            $content = $this->content->create(
                [
                'user_id' => $userId,
                'section_id' => $sectionId,
                'type' => $type,
                'substance' => $substance,
                ]
            );
            $data = ['id' => $content->id, 'type' => $type, 'substance' => $substance];
            return $data;
        }
        return null;
    }
}
