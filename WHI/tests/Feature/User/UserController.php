<?php
declare(strict_types=1);

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends TestCase
{
    use RefreshDatabase;
    /**
     * ユーザー登録のテスト
     *
     * test
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
     * test
     *
     * @return void
     */
    public function destroy()
    {
        // ユーザー情報
        $userId = 1;
        $name = 'test';
        $email = 'test@gmail.com';
        $password = 'w44wwwww';

        // ユーザーの登録を行うリクエストを送信
        $this->post('/user', ['name' => $name, 'email' => $email, 'password' => $password]);

        // プロフィールのセクションの作成
        $sectionId = 1;
        $storeData = [
        'userName' => $name,
        'sectionName' => 'test'
        ];
        $this->post('/user/'.$userId.'/sections', $storeData);

        // プロフィールのコンテンツの作成
        $contentData = [
        'userId' => $userId,
        'sectionId' => $sectionId,
        'type' => 'text',
        'substance' => 'testtest',
        ];
        $this->post('/user/'.$userId.'/contents', $contentData);

        // ブログの作成
        $blogData = [
        'userId' => $userId,
        'sectionId' => $sectionId,
        'title' => Str::random(255),
        'text' => Str::random(10000)
        ];
        $response = $this->post('/blog/', $blogData);

        // ユーザーの退会を行うリクエストを送信
        $response = $this->delete('/user/'.$userId, ['name' => $name]);

        // 存在しないユーザーの退会を行うリクエストを送信
        //$response = $this->delete('/user/2', ['email' => $email]);

        // リクエスト及びDBの値の確認
        $response->assertStatus(200);
        // DBからユーザーが削除されているか
        $this->assertDatabaseMissing('users', ['id' => $userId]);
        // DBから削除されたユーザーのプロフィールが削除されているか
        $this->assertDatabaseMissing('profiles', ['user_id' => $userId]);
        // DBから削除されたユーザーのセクションが削除されているか
        $this->assertDatabaseMissing('sections', ['user_id' => $userId]);
        // DBから削除されたユーザーのコンテンツが削除されているか
        $this->assertDatabaseMissing('contents', ['user_id' => $userId]);
        // DBから削除されたユーザーのブログが削除されているか
        $this->assertDatabaseMissing('blogs', ['user_id' => $userId]);
    }

    /**
     * ユーザー情報の更新のテスト
     *
     * test
     *
     * @return void
     */
    public function update()
    {
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

    /**
     * ユーザー情報の取得のテスト
     *
     * @test
     *
     * @return void
     */
    public function show()
    {
        // ユーザー情報
        $userId = 1;
        $name = 'test';
        $email = 'test@gmail.com';
        $password = 'w44wwwww';

        // ユーザーの登録を行うリクエストを送信
        $this->post('/user', ['name' => $name, 'email' => $email, 'password' => $password]);

        // ユーザーの取得を行うリクエストを送信
        $response = $this->get('/user/'.$userId);

        // リクエストの結果の確認
        $trueData = [
            'name' => $name,
            'icon' => null,
        ];
        $response->assertExactJson($trueData, true);
    }
}
