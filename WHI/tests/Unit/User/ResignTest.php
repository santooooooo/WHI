<?php

namespace Tests\Unit\User;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Services\User\SignUp;
use App\Services\User\Resign;

class ResignTest extends TestCase
{
    use RefreshDatabase;
    /**
     * ユーザーの退会機能のテスト
     *
     * @test
     * @return void
     */
    public function remove()
    {
        // ユーザー情報
        $name = 'Jamboo';
        $email = 'Jamboo@gmail.com';
        $password = 'Jamboo';

        // ユーザー情報の登録
        $db = new User();
        $signUp = new SignUp($db);
        $signUp->record($name, $email, $password);

        // 2件目のユーザー情報の登録
        $name2 = 'Jamboos';
        $email2 = 'Jamboos@gmail.com';
        $password2 = 'Jamboo';
        $signUp->record($name2, $email2, $password2);

        // 登録されたユーザー情報の取得
        $user = User::find(1);

        // ユーザー情報の削除
        $domain = new Resign();
        $domain->remove($name, $email);

        // 異なるユーザーのユーザー名とパスワードを入力したときの挙動チェック
        //$domain->remove($name2, $email);

        $this->assertDeleted($user);
    }
}
