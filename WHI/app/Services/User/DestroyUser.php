<?php
declare(strict_types=1);

namespace App\Services\User;

use App\Models\User;
use App\Models\Profile;
use App\Models\Blog;
use App\Models\Content;
use App\Models\Section;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

final class DestroyUser
{
    public int $id;
    public string $name;

    public function __construct(int $id, string $name)
    {
        $this->user = new User();
        $this->profile = new Profile();
        $this->blog = new Blog();
        $this->content = new Content();
        $this->section = new Section();
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * 既存のユーザーの情報を削除する
     */
    public function destroy(): bool
    {
        $isUser = $this->user->where('id', $this->id)->where('name', $this->name)->exists();

        if($isUser) {

            try{
                DB::beginTransaction();
                // プロフィールの削除
                $iconPath = $this->profile->where('user_id', $this->id)->value('icon');
                if(!is_null($iconPath)) {
                    $this->deleteIcon($iconPath);
                }
                $this->profile->where('user_id', $this->id)->delete();

                // ブログの削除
                $this->blog->where('user_id', $this->id)->delete();

                // コンテンツの削除
                $this->content->where('user_id', $this->id)->delete();

                // 項目の削除
                $this->section->where('user_id', $this->id)->delete();

                // ユーザーの削除
                $this->user->where(
                    [
                    ['id', '=', $this->id],
                    ['name', '=', $this->name]
                    ]
                )->delete();

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

    /**
     * ユーザーのアイコンの削除
     */
    private function deleteIcon(string $path): void
    {
        Storage::disk('s3')->delete($path);
        return;
    }
}
?>
