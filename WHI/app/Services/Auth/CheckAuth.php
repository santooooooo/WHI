<?php
declare(strict_types=1);

namespace App\Services\Auth;

use App\Models\Auth;
use App\Models\User;

class CheckAuth
{
    public function __construct()
    {
        $this->auth = new Auth();
        $this->user = new User();
    }

    /**
     * リクエストの認証情報の確認
     */
    public function check(string $identification, string $name): bool
    {
        $email = $this->auth->where('identification', $identification)->value('email');
        if(!is_null($email)) {
            $userName = $this->user->where('email', $email)->value('name');
            return $userName === $name;
        }
        return false;
    }
}
