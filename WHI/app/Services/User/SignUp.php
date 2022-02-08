<?php
declare(strict_types=1);

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

final class SignUp
{
    public function __construct()
    {
        $this->user = new User();
    }

    /**
     * 新規登録の値が正常である場合は登録したユーザーのIDを、正常でなければ何もせずに0を返す
     */
    public function record(string $name, string $email, string $password): int
    {
        $valueCheck = strlen($name) > 0 && strlen($email) > 0 && strlen($password) > 0;
        $isNew = $this->isNew($email);

        if($valueCheck && $isNew) {
            $this->user->create(
                [
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password)
                ]
            );

            $id = $this->user->where('email', $email)->value('id');
            return $id;
        }
        return 0;
    }

    private function isNew(string $email): bool
    {
        return !$this->user->where('email', $email)->exists();
    }
}
?>
