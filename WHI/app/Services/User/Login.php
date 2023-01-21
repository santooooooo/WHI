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
        $userInfo = $this->isUser($email, $password);
        if(!$userInfo) {
            return [];
        } else {
            return $userInfo;
        }
    }

    private function isUser(string $email, string $password)
    {
        $user = $this->user->where('email', $email)->first();
        if(is_object($user) && Hash::check($password, $user->password)) {
            return ['id' => $user->id, 'name' => $user->name];
        } else {
            return false;
        }
    }
}
