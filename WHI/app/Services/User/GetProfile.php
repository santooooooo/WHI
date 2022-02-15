<?php
declare(strict_types=1);

namespace App\Services\User;

use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Profile;

final class GetProfile
{
    public function __construct()
    {
        $this->user = new User();
        $this->profile = new Profile();
    }

    /*
     * ユーザーのプロフィールの取得
     */
    public function getInfo(int $id): array
    {
        $isUser = $this->user->where('id', $id)->exists();
        if($isUser) {
            $eloquent = $this->profile->where('user_id', $id)->get();
            $profile = $eloquent[0];

            if(!is_null($profile->icon)) {
                $icon = $this->getIcon($profile->icon);
            } else {
                $icon = null;
            }

            $data = [
            'icon' => $icon,
            'career' => $profile->career,
            'title' => $profile->title,
            'text' => $profile->text,
            'mail' => $profile->mail,
            'twitter' => $profile->twitter,
            ];

            return $data;
        }
    }

    /*
     * AmazonS3からアイコンの画像を取得
     */
    private function getIcon(string $path): string
    {
        $icon = Storage::disk('s3')->url($path);
        return $icon;
    }
}
