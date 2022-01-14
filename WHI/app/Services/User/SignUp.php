<?php
declare(strict_types=1);

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

final class SignUp
{
    public function record(string $name, string $email, string $password): bool
    {
        $valueCheck = strlen($name) > 0 && strlen($email) > 0 && strlen($password) > 0;

        if($valueCheck) {
            $db = new User();
            $db->create(
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
}
?>
