<?php
declare(strict_types=1);

namespace Tests\Unit\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Services\Auth\SetResetPasswordAuth;

class SetResetPasswordAuthTest extends TestCase
{
    use RefreshDatabase;
    /**
     * パスワード再設定ページ用の認証情報の保存
     *
     * @test
     * @return void
     */
    public function set()
    {
        $id = 1;
        $email = 'test@test.com';

        $domain = new SetResetPasswordAuth();
        $domain->set($email);
        $data = [
        'id' => $id,
        'email' => $email,
        ];
        $this->assertDatabaseHas('reset_passwords', $data);
    }
}
