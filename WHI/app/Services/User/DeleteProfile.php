<?php
declare(strict_types=1);

namespace App\Services\User;

use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Profile;

final class DeleteProfile
{
    public function __construct()
    {
        $this->user = new User();
        $this->profile = new Profile();
    }

    // ユーザーのプロフィールの削除
    public function deleteProfile(int $id, string $name): bool
    {
        $isUser = $this->user->where('id', $id)->where('name', $name)->exists();
        if($isUser) {

            // ユーザーのアイコンの削除
            $iconPath = $this->profile->where('user_id', $id)->value('icon');
            if(!is_null($iconPath)) {
                $this->deleteIcon($iconPath);
            }

            $this->profile->where('user_id', $id)->delete();
            return true;
        }
        return false;
    }

    private function deleteIcon(string $path): void
    {
        Storage::disk('s3')->delete($path);
        return;
    }
}
