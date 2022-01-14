<?php

namespace Tests\Unit\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Services\User\SignUp;
use Illuminate\Support\Facades\Hash;

class SignUpTest extends TestCase
{
    use RefreshDatabase;
    /**
     * ユーザー登録のテスト
     *
     * @test
     * @return void
     */
    public function record()
    {
        $name = 'Jamboo';
        $email = 'Jamboo@gmail.com';
        $password = 'Jamboo';

        $domain = new SignUp();
        $domain->record($name, $email, $password);

        $user = User::find(1);

        $result = $name === $user->name && $email === $user->email && Hash::check($password, $user->password);

        $this->assertTrue($result);
    }
}
