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
     * @return int | bool
     */
    public function update(int $id, string $password, string $newName, string $newEmail, string $newPassword)
    {
        // 入力値のチェック
        $valueCheck = strlen($password) > 0 && strlen($newName) > 0 && strlen($newEmail) > 0 && strlen($newPassword) > 0;
        // 入力者はユーザーであるかのチェック
        $isUser = $this->isUser($id, $password);
        // 新たなメールアドレスが他のユーザーと被らないかのチェック
        $notDoubleEmail = $this->notDoubleEmail($id, $newEmail);

        if($valueCheck && $isUser && $notDoubleEmail) {
            $this->user->where('id', $id)->update(
                [
                'name' => $newName,
                'email' => $newEmail,
                'password' => Hash::make($newPassword)
                ]
            );
            $id = $this->user->where('email', $newEmail)->value('id');
            return $id;
        }
        return false;
    }

    private function isUser(int $id, string $password): bool
    {
        $user = $this->user->where('id', $id)->first();
        if(is_object($user)) {
            return Hash::check($password, $user->password);
        }
        return false;
    }

    private function notDoubleEmail(int $id, $email): bool
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
