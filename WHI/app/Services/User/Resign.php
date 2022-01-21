<?php
declare(strict_types=1);

namespace App\Services\User;

use App\Models\User;

final class Resign
{
    public function __construct()
    {
        $this->user = new User();
    }

    public function remove(int $id,string $email): bool
    {
        $valueCheck = $id > 0 && strlen($email) > 0;
        $isUser = $this->isUser($email);

        if($valueCheck && $isUser) {
            $this->user->where(
                [
                ['id', '=', $id],
                ['email', '=', $email]
                ]
            )->delete();
            return true;
        }
        return false;
    }

    private function isUser(string $email): bool
    {
        return $this->user->where('email', $email)->exists();
    }
}
