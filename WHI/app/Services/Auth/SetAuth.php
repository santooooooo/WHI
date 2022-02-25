<?php
declare(strict_types=1);

namespace App\Services\Auth;

use App\Models\Auth;
use Illuminate\Support\Str;

class SetAuth
{
    public function __construct()
    {
        $this->auth = new Auth();
    }

    /**
     * リクエストの認証情報の保存
     */
    public function set(string $email): string
    {
        // 前のデータがある場合は、そのデータを削除し、新たなデータを保存
        $record = $this->auth->where('email', $email)->exists();
        if($record) {
            $this->auth->where('email', $email)->delete();
        }
        $identification = Str::random(40);
        $this->auth->create(
            [
            'email' => $email,
            'identification' => $identification,
            ]
        );
        return $identification;
    }
}
