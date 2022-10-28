<?php
declare(strict_types=1);

namespace App\Services\User;

use App\Models\User;
use App\Models\Section;
use App\Models\Content;;

final class GetContent
{
    public function __construct(int $id)
    {
        $this->user = User::find($id);
    }

    /**
     * プロフィールの全てのコンテンツの取得
     *
     * @return array<int, array{
     * id: int,
     * section_id: int,
     * type: string,
     * substance: string,
     * }> | void
     */
    public function index()
    {
        if(is_null($this->user)) {
            return;
        }

        $contents = $this->user->contents;
        $data = [];
        foreach($contents as $content)
        {
            $data[] = ['id' => $content->id, 'section_id' => $content->section_id, 'type' => $content->type, 'substance' => $content->substance];
        }
        return $data;
    }
}
