<?php
declare(strict_types=1);

namespace App\Services\User;

use App\Models\User;
use App\Models\Section;
use App\Models\Content;;

final class UpdateContent
{
    public function __construct()
    {
        $this->user = new User();
        $this->section = new Section();
        $this->content = new Content();
    }

    /**
     * プロフィールのコンテンツの更新
     *
     * @return null | array{
     * id: int,
     * type: string,
     * substance: string,
     * }
     */
    public function update(int $userId, int $sectionId,int $contentId, string $substance)
    {
        $substanceCheck = strlen($substance) > 0;
        $isUser = $this->user->where('id', $userId)->exists();
        $isSection = $this->section->where('id', $sectionId)->where('user_id', $userId)->exists();
        $isContent = $this->content->where('id', $contentId)->where('user_id', $userId)->where('section_id', $sectionId)->exists();

        if($substanceCheck && $isUser && $isSection && $isContent) {
            $this->content->where('id', $contentId)->update(
                [
                'substance' => $substance
                ]
            );
	    $content = $this->content->where('id', $contentId)->first();
            $data = ['id' => $content->id, 'section_id' => $content->section_id, 'type' => $content->type, 'substance' => $content->substance];
            return $data;
        }
        return null;
    }
}
