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

    // ユーザーの退会
    public function remove(int $id,string $name): void
    {
        $valueCheck = $id > 0 && strlen($name) > 0;
        $isUser = $this->isUser($name);

        if($valueCheck && $isUser) {
            $this->user->where(
                [
                ['id', '=', $id],
                ['name', '=', $name]
                ]
            )->delete();
            return;
        }
        return;
    }

    private function isUser(string $name): bool
    {
        return $this->user->where('name', $name)->exists();
    }
}
