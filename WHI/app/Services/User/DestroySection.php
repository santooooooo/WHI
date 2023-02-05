<?php
declare(strict_types=1);

namespace App\Services\User;

use App\Models\Blog;
use App\Models\Content;
use App\Models\Section;
use Illuminate\Support\Facades\DB;

final class DestroySection
{
    public int $userId;
    public int $sectionId;

    public function __construct(int $userId, int $sectionId)
    {
        $this->blog = new Blog();
        $this->content = new Content();
        $this->section = new Section();
        $this->userId = $userId;
        $this->sectionId = $sectionId;
    }

    /**
     * 既存の項目の情報を削除する
     */
    public function destroy(): bool
    {
        $isSection = $this->section->where('user_id', $this->userId)->where('id', $this->sectionId)->exists();

        if($isSection) {

            try{
                DB::beginTransaction();

                // ブログの削除
                $this->blog->where('user_id', $this->userId)->where('section_id', $this->sectionId)->delete();

                // コンテンツの削除
                $this->content->where('user_id', $this->userId)->where('section_id', $this->sectionId)->delete();

                // コンテンツの削除
                $this->section->where('user_id', $this->userId)->where('id', $this->sectionId)->delete();

                DB::commit();
                return true;
            } catch(\Exception $e) {
                DB::rollBack();

                return false;
            }

        } else {
            return false;
        }
    }
}
?>
