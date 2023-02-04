<?php
declare(strict_types=1);

namespace App\Services\Auth;

use App\Models\ResetPassword;
use App\Models\User;
use Illuminate\Support\Str;

final class SetResetPasswordAuth
{
    public function __construct()
    {
        $this->resetPassword = new ResetPassword();
        $this->user = new User();
    }

    /**
     * パスワード再設定ページ用の認証情報の保存
     */
    public function set(string $email): string | bool
    {
        // 前のデータがある場合は、そのデータを削除し、新たなデータを保存
        $record = $this->resetPassword->where('email', $email)->exists();
        if($record) {
            $this->resetPassword->where('email', $email)->delete();
        } 

        // ユーザーである場合のみ、パスワード再設定用のデータを作成
        $isUser = $this->user->where('email', $email)->exists();
        if($isUser) {
               $identification = Str::random(10);
            $this->resetPassword->create(
                [
                'email' => $email,
                'identification' => $identification,
                ]
            );
                      return $identification;
        } else {
            return false;
        }
    }
}
