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

    public function record(string $name, string $email, string $password): bool
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

            return true;
        }
        return false;
    }

    private function isNew(string $email): bool
    {
        return !$this->user->where('email', $email)->exists();
    }
}
?>
