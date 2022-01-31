<?php
declare(strict_types=1);

namespace Tests\Unit\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Services\User\Login;
use App\Services\User\SignUp;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    /**
     * ログイン機能のテスト
     *
     * @test
     * @return void
     */
    public function execute()
    {
        // ユーザー情報
        $name = 'Jamboo';
        $email = 'Jamboo@gmail.com';
        $password = 'Jamboo';

        // ユーザー情報の登録
        $signUp = new SignUp();
        $signUp->record($name, $email, $password);

        // 誤ったデータの入力
        //$falseName = 'Jamboooo';
        //$falseEmail = 'Jamboooooo@gmail.com';

        $login = new Login();
        $test = $login->execute($name, $email);

        $this->assertTrue($test);
    }
}
