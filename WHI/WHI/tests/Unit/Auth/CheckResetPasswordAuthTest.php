<?php
declare(strict_types=1);

namespace Tests\Unit\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Services\User\SignUp;
use App\Services\Auth\SetResetPasswordAuth;
use App\Services\Auth\CheckResetPasswordAuth;
use App\Models\ResetPassword;

class CheckResetPasswordAuthTest extends TestCase
{
    use RefreshDatabase;
    /**
     * パスワード再設定ページ用の認証情報の確認
     *
     * @test
     * @return void
     */
    public function check()
    {
        // ユーザー情報
        $id = 1;
        $email = 'Jamboo@gmail.com';

        // 認証情報の保存
        $setResetPasswordAuth = new SetResetPasswordAuth();
        $setResetPasswordAuth->set($email);

        // 認証情報のチェック
        $identification = ResetPassword::find($id)->identification;
        $domain = new CheckResetPasswordAuth();
        $result = $domain->check($identification);

        $this->assertSame($email, $result);
    }
}
