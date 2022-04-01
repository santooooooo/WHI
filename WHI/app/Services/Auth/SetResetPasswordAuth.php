<?php
declare(strict_types=1);

namespace App\Services\Auth;

use App\Models\ResetPassword;
use Illuminate\Support\Str;

final class SetResetPasswordAuth
{
    public function __construct()
    {
        $this->resetPassword = new ResetPassword();
    }

    /**
     * パスワード再設定ページ用の認証情報の保存
     */
    public function set(string $email): string
    {
        // 前のデータがある場合は、そのデータを削除し、新たなデータを保存
        $record = $this->resetPassword->where('email', $email)->exists();
        if($record) {
            $this->resetPassword->where('email', $email)->delete();
        }
        $identification = Str::random(10);
        $this->resetPassword->create(
            [
            'email' => $email,
            'identification' => $identification,
            ]
        );
        return $identification;
    }
}
