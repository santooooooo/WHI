<?php
declare(strict_types=1);

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class BasicController extends TestCase
{
    use RefreshDatabase;
    /**
     * ユーザー登録のテスト
     *
     * @test
     *
     * @return void
     */
    public function store()
    {
        // ユーザー情報
        $id = 1;
        $name = 'test';
        $email = 'test@gmail.com';
        $password = 'w44wwwww';

        // ユーザーの登録を行うリクエストを送信
        $response = $this->post('/user', ['name' => $name, 'email' => $email, 'password' => $password]);

        // リクエスト回数制限の確認
        //for($i=0;$i < 61;$i++) {
        //       $response = $this->post('/user', ['name' => $name, 'email' => $email, 'password' => $password]);
        //}

        // データベースから登録されたユーザー情報を取得
        $user = User::find($id);

        // データベースの情報が元の情報と同じであることを確認
        $result = $name === $user->name && $email === $user->email && Hash::check($password, $user->password);

        $response->assertStatus(200);
        $this->assertTrue($result);

        // 返信データが元のユーザーの情報と一致しているか確認
        $data = ['id' => $id,'name' => $name];
        $response->assertJson($data);

        // 新規ユーザー用のプロフィールDBが作成されているか確認
        $this->assertDatabaseHas('profiles', ['user_id' => 1]);
    }

    /**
     * ユーザー退会のテスト
     *
     * @test
     * @return void
     */
    public function destroy()
    {
        // このファイル内の全てのテストを同時に行う際のデータベースの初期化
        DB::table('users')->truncate();

        // ユーザー情報
        $id = 1;
        $name = 'test';
        $email = 'test@gmail.com';
        $password = 'w44wwwww';

        // ユーザーの登録を行うリクエストを送信
        $this->post('/user', ['name' => $name, 'email' => $email, 'password' => $password]);

        // 新規ユーザー用のプロフィールDBが作成されているか確認
        $this->assertDatabaseHas('profiles', ['user_id' => $id]);

        // ユーザーの退会を行うリクエストを送信
        $response = $this->delete('/user/'.$id, ['name' => $name]);

        // 存在しないユーザーの退会を行うリクエストを送信
        //$response = $this->delete('/user/2', ['email' => $email]);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('users', ['id' => $id]);
        $this->assertDatabaseMissing('profiles', ['user_id' => $id]);
    }

    /**
     * ユーザー情報の更新のテスト
     *
     * @test
     * @return void
     */
    public function update()
    {
        // このファイル内の全てのテストを同時に行う際のデータベースの初期化
        DB::table('users')->truncate();

        // ユーザー情報
        $id = 1;
        $name = 'test';
        $email = 'test@gmail.com';
        $password = 'w44wwwww';

        // ユーザーの登録を行うリクエストを送信
        $this->post('/user', ['name' => $name, 'email' => $email, 'password' => $password]);

        // 更新するデータ
        $updateName = 'Hello';
        $updateEmail = 'Hello@gmail.com';
        $updatePassword = 'Hello99';

        $data = [
        'password' => $password,
        'newName' => $updateName,
        'newEmail' => $updateEmail,
        'newPassword' => $updatePassword,
        ];

        $response = $this->put('/user/'.$id, $data);
        $response->assertStatus(200);

        // ユーザー用のプロフィールDBが更新されているか確認
        $this->assertDatabaseHas('users', ['id' => $id, 'name' => $data['newName'], 'email' => $data['newEmail']]);

        // ユーザー情報
        $id2 = 2;
        $name2 = 'test';
        $email2 = 'test2@gmail.com';
        $password2 = 'w44wwwww';

        // ユーザーの登録を行うリクエストを送信
        $this->post('/user', ['name' => $name2, 'email' => $email2, 'password' => $password2]);

        // 更新するデータ
        $updateEmail2 = 'Hello@gmail.com';

        $data2 = [
        'password' => $password2,
        'newName' => null,
        'newEmail' => $updateEmail2,
        'newPassword' => null,
        ];

        $response = $this->put('/user/'.$id2, $data2);
        $message = ['double email'];
        $response->assertExactJson($message);
    }

}
