<?php

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

    public function write(string $id, string $name, $icon = null, string $carrer = null, string $title = null, string $text = null, string $mail = null, string $twitter = null)
    {
        $isUser = $this->user->where('id', $id)->where('name', $name)->exists();
        if($isUser) {
            $isNew = !$this->profile->where('user_id', $id)->exists();
            if($isNew) {
                $this->profile->create(
                    [
                    'user_id' => $id,
                    'icon' => $icon,
                    'carrer' => $carrer,
                    'title' => $title,
                    'text' => $text,
                    'mail' => $mail,
                    'twitter' => $twitter,
                    ]
                );
                return true;
            }
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

    private function storeIcon($icon = null)
    {
        $isFile = is_file($icon);
        if($icon !== null && $isFile) {
            $path = Storage::disk('s3')->put('users', $icon);
            return $path;
        }
        return null;
    }
}
