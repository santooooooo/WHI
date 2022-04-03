<?php
declare(strict_types=1);

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

final class ResetPassword
{
    public function __construct()
    {
        $this->user = new User();
    }

    /**
     * 既存のユーザーのパスワードを更新する
     *
     */
    public function update(string $email, string $password): bool
    {
        // 入力者はユーザーであるかのチェック
        $isUser = $this->user->where('email', $email)->exists();
        if($isUser) {
        // ユーザーのパスワードを更新
                    $this->user->where('email', $email)->update(
                        [
                        'password' => Hash::make($password)
                        ]
                    );
            return true;
        }
        return false;
    }
}
