<?php
declare(strict_types=1);

namespace App\Services\User;

use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Profile;

final class GetUser
{
    public function __construct()
    {
        $this->user = new User();
        $this->profile = new Profile();
    }

    /*
     * ユーザー情報の取得
     * @return array {
     * id: int,
     * name: string,
     * icon: string,
     * } | null
     */
    public function getInfo(int $id)
    {
        $isUser = $this->user->where('id', $id)->exists();
        if($isUser) {
            $user = $this->user->where('id', $id)->first();
            $profile = $this->profile->where('user_id', $id)->first();

            if(!is_null($profile->icon)) {
                $icon = $this->getIcon($profile->icon);
            } else {
                $icon = null;
            }

            $data = [
            'id' => $user->id,
            'name' => $user->name,
            'icon' => $icon,
            ];
        
            return $data;
        }
        return null;
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
