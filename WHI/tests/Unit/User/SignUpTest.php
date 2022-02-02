<?php
declare(strict_types=1);

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
        // ユーザー情報
        $name = 'Jamboo';
        $email = 'Jamboo@gmail.com';
        $password = 'Jamboo';

        // ユーザー情報の登録
        $domain = new SignUp();
        $domain->record($name, $email, $password);

        // データベースから登録されたユーザー情報を取得
        $user = User::find(1);

        // データベースの情報が元の情報と同じであることを確認
        $result = $name === $user->name && $email === $user->email && Hash::check($password, $user->password);

        $this->assertTrue($result);

        // 同じ情報の登録ができないことを確認
        $domain = new SignUp($user);
        $domain->record($name, $email, $password);

        $falseUser = User::find(2);
        $this->assertTrue(null === $falseUser);
    }
}
