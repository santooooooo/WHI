<?php
declare(strict_types=1);

namespace Tests\Unit\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Services\User\SignUp;
use App\Services\Auth\SetAuth;
use App\Services\Auth\CheckAuth;
use App\Models\Auth;

class CheckAuthTest extends TestCase
{
    use RefreshDatabase;
    /**
     * リクエストの認証情報の確認のテスト
     *
     * @test
     * @return void
     */
    public function check()
    {
        // ユーザー情報
        $id = 1;
        $name = 'Jamboo';
        $email = 'Jamboo@gmail.com';
        $password = 'Jamboo';

        // 認証情報の保存
        $setAuth = new SetAuth();
        $setAuth->set($email);

        // ユーザー情報の登録
        $domain = new SignUp();
        $domain->record($name, $email, $password);

        // 認証情報のチェック
        $identification = Auth::find($id)->identification;
        $domain = new CheckAuth();
        $result = $domain->check($identification, $id, $name);

        $this->assertTrue($result);
    }
}
