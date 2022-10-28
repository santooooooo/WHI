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
        $result = $domain->record($name, $email, $password);

        // データベースから登録されたユーザー情報を取得
        $user = User::find(1);

        // データベースの情報が元の情報と同じであることを確認
        $dataCheck = $name === $user->name && $email === $user->email && Hash::check($password, $user->password);

        $this->assertTrue($result === 1);
        $this->assertTrue($dataCheck);

        // 同じ情報の登録ができないことを確認
        $noUser = $domain->record($name, $email, $password);

        $this->assertTrue($noUser === 0);
    }
}
