<?php
declare(strict_types=1);

namespace Tests\Unit\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Services\User\SignUp;
use App\Services\User\ResetPassword;
use Illuminate\Support\Facades\Hash;

class ResetPasswordTest extends TestCase
{
    use RefreshDatabase;
    /**
     * 既存のユーザーのパスワードを更新する
     *
     * @test
     * @return void
     */
    public function update()
    {
        // ユーザー情報
        $id = 1;
        $name = 'Jamboo';
        $email = 'Jamboo@gmail.com';
        $password = 'Jamboo';

        // ユーザー情報の登録
        $signUp = new SignUp();
        $signUp->record($name, $email, $password);

        // 更新するデータ
        $updatePassword = 'Hello88';

        // パスワードのリセット
        $domain = new ResetPassword();
        $domain->update($email, $updatePassword);

        // データベースから登録されたユーザー情報を取得
        $user = User::find($id);

        // パスワードがリセットされたか確認
        $this->assertTrue(Hash::check($updatePassword, $user->password));
    }
}
