<?php
declare(strict_types=1);

namespace App\Services\User;

use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Profile;

final class WriteProfile
{
    public function __construct()
    {
        $this->user = new User();
        $this->profile = new Profile();
    }

    /*
     * ユーザーのプロフィールの保存または更新
     */
    public function write(int $id, string $name, $icon = null, string $carrer = null, string $title = null, string $text = null, string $mail = null, string $twitter = null): bool
    {
        $isUser = $this->user->where('id', $id)->where('name', $name)->exists();
        if($isUser) {
            // 新しいユーザーであった場合、内容が空のプロフィール欄を作成
            $isNew = !$this->profile->where('user_id', $id)->exists();
            if($isNew) {
                $this->profile->create(
                    [
                    'user_id' => $id,
                    ]
                );
                return true;
            }
                $this->deleteIcon($id, $icon);
                $path = $this->storeIcon($id, $icon);
                $this->profile->where('user_id', $id)->update(
                    [
                    'icon' => $path,
                    'career' => $carrer,
                    'title' => $title,
                    'text' => $text,
                    'mail' => $mail,
                    'twitter' => $twitter,
                    ]
                );
                return true;
        }
        return false;
    }

    /**
     * ユーザーのアイコンをAmazonS3へ保存
     *
     * @return string | null
     */
    private function storeIcon(int $id, $newIcon)
    {
        $oldIcon = $this->profile->where('user_id', $id)->value('icon');
        $env = env('APP_ENV');
        if(!is_null($newIcon)) {

            // テストの時はテスト用のディレクトリへ保存する
            if($env === 'testing') {
                $path = Storage::disk('s3')->put('/test/users/'.$id.'/profile', $newIcon, 'public');
                return $path;
            }

            // アイコンの保存
            $path = Storage::disk('s3')->put('users/'.$id.'/profile', $newIcon, 'public');
            return $path;
        } elseif(!is_null($oldIcon)) {
            $path = Storage::disk('s3')->path($oldIcon);
            return $path;
        }
        return null;
    }

    /**
     * もしアイコンが存在していた場合、古いアイコンを削除
     */
    private function deleteIcon(int $id,$newIcon): void
    {
        $oldIcon = $this->profile->where('user_id', $id)->value('icon');
        if(!is_null($newIcon) && !is_null($oldIcon)) {
            Storage::disk('s3')->delete($oldIcon);
            return;
        }
        return;
    }
}
