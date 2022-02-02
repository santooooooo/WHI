<?php
declare(strict_types=1);

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

final class Login
{
    public function __construct()
    {
        $this->user = new User();
    }

    public function execute(string $email, string $password): array
    {
        $check = $this->isUser($email, $password);
        if($check) {
            return $this->userInfo($email);
        }
        return [];
    }

    private function isUser(string $email, string $password): bool
    {
        $user = $this->user->where('email', $email)->first();
        if(is_object($user)) {
            return Hash::check($password, $user->password);
        }
        return false;
    }

    private function userInfo(string $email): array
    {
        $name = $this->user->where('email', $email)->value('name');
        return ['name' => $name, 'email' => $email];
    }
}
