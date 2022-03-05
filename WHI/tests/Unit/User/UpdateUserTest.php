<?php

namespace Tests\Unit\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Services\User\SignUp;
use App\Services\User\UpdateUser;
use Illuminate\Support\Facades\Hash;

class UpdateUserTest extends TestCase
{
    use RefreshDatabase;
    /**
     * ユーザーの情報の更新のテスト
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
        $result = $signUp->record($name, $email, $password);

        // 更新するデータ
        $updateName = 'Hello';
        $updateEmail = 'Hello@gmail.com';
        $updatePassword = 'Hello88';

        // ユーザーの情報を更新
        $domain = new UpdateUser();
        $result = $domain->update($id, $password, $updateName, $updateEmail, $updatePassword);
        $resultCheck = [
        'id' => $id,
        'name' => $updateName,
        ];
        $this->assertTrue($result === $resultCheck);

        // データベースから登録されたユーザー情報を取得
        $user = User::find($id);

        // 更新されたデータベースの情報が元の情報と同じであることを確認
        $dataCheck = $updateName === $user->name && $updateEmail === $user->email && Hash::check($updatePassword, $user->password);
        $this->assertTrue($dataCheck);

        // もう一人のユーザー情報
        $id = 2;
        $email2 = 'Jamboo2@gmail.com';

        // もう一人のユーザー情報の登録
        $signUp = new SignUp();
        $result = $signUp->record($name, $email2, $password);

        // もう一人のユーザーが一人目のユーザーと同じEmailで更新しようとする
        $domain = new UpdateUser();
        $result = $domain->update($id, $password, $updateName, $updateEmail, $updatePassword);
        $this->assertTrue($result === 'double email');
    }
}
