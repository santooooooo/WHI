<?php
declare(strict_types=1);

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

final class UpdateUser
{
    public function __construct()
    {
        $this->user = new User();
    }


    /**
     * 既存のユーザーの情報を更新する
     *
     * @return string | array
     */
    public function update(int $id, string $password, ?string $newName, ?string $newEmail, ?string $newPassword)
    {
        // 入力値がnullであった場合、元の値を入れる
        $user = $this->user->where('id', $id)->first();
        $newName = $newName ?? $user->name;
        $newEmail = $newEmail ?? $user->email;
        $newPassword = $newPassword ?? $password;

        // 入力者はユーザーであるかのチェック
        $isUser = $this->isUser($id, $password);

        // 新たなメールアドレスが他のユーザーと被らないかのチェック
        $notDoubleEmail = $this->notDoubleEmail($id, $newEmail);

        if($isUser) {
            if($notDoubleEmail) {
                    $this->user->where('id', $id)->update(
                        [
                        'name' => $newName,
                        'email' => $newEmail,
                        'password' => Hash::make($newPassword)
                        ]
                    );
                      return ['id' => $id, 'name' => $newName];
            }
            return 'double email';
        }
        return 'password wrong';
    }

    private function isUser(int $id, string $password): bool
    {
        $user = $this->user->where('id', $id)->first();
        if(is_object($user)) {
            return Hash::check($password, $user->password);
        }
        return false;
    }

    private function notDoubleEmail(int $id, string $email): bool
    {
        $user = $this->user->where('id', $id)->first();
        if($email === $user->email) {
            return true;
        }
        $double = $this->user->where('email', $email)->exists();
        if($double) {
            return false;
        }
        return true;
    }
}
