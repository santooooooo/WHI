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

    public function write(int $id, string $name, $icon = null, string $carrer = null, string $title = null, string $text = null, string $mail = null, string $twitter = null): bool
    {
        $isUser = $this->user->where('id', $id)->where('name', $name)->exists();
        if($isUser) {
            $isNew = !$this->profile->where('user_id', $id)->exists();
            if($isNew) {
                $this->profile->create(
                    [
                    'user_id' => $id,
                    ]
                );
                return true;
            }
                $this->deleteIcon($id);
                $path = $this->storeIcon($icon);
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
    private function storeIcon($icon)
    {
        $isObject = is_object($icon);
        if(!is_null($icon) && $isObject) {
            $path = Storage::disk('s3')->put('users', $icon, 'public');
            return $path;
        }
        return null;
    }

    /**
     * もしアイコンが存在していた場合、古いアイコンを削除
     */
    private function deleteIcon(int $id): void
    {
        $icon = $this->profile->where('user_id', $id)->value('icon');
        if(!is_null($icon)) {
            Storage::disk('s3')->delete($icon);
            return;
        }
        return;
    }
}
