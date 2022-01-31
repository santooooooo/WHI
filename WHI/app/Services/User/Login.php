<?php
declare(strict_types=1);

namespace App\Services\User;

use App\Models\User;

final class Login
{
    public function __construct()
    {
        $this->user = new User();
    }

    public function execute(string $name, string $email): bool
    {
        $check = $this->isUser($name, $email);
        if($check) {
            return true;
        }
        return false;
    }

    private function isUser(string $name, string $email): bool
    {
        return $this->user->where('email', $email)->where('name', $name)->exists();
    }
}
