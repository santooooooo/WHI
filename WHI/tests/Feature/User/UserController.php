<?php
declare(strict_types=1);

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;
use App\Models\Section;
use App\Models\Content;
use App\Models\Blog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends TestCase
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

        // リクエストが正常になされているか及び認証機能が正常に動いているか
        $response->assertStatus(200);
        $this->assertTrue($result);
        $this->assertAuthenticated();

        // 返信データが元のユーザーの情報と一致しているか確認
        $data = ['id' => $id,'name' => $name];
        $response->assertJson($data);

        // 新規ユーザー用のプロフィールDBが作成されているか確認
        $this->assertDatabaseHas('profiles', ['user_id' => $user->id]);
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
        // テスト用のユーザーデータの作成
        $user = User::factory()->create();
        Profile::factory()->for($user)->create();
        $section = Section::factory()->for($user)->create();
        Content::factory()->state(['user_id' => $user->id, 'section_id' => $section->id])->create();
        Blog::factory()->state(['user_id' => $user->id, 'section_id' => $section->id])->create();

        // ユーザーの退会を行うリクエストを送信
        $response = $this->actingAs($user)->delete('/user/'.$user->id);

        // 存在しないユーザーの退会を行うリクエストを送信
        //$response = $this->delete('/user/2', ['email' => $email]);

        // リクエスト及びDBの値の確認
        $response->assertStatus(200);
        $trueResult = ['Success'];
        $response->assertExactJson($trueResult, true);
        // DBからユーザーが削除されているか
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        // DBから削除されたユーザーのプロフィールが削除されているか
        $this->assertDatabaseMissing('profiles', ['user_id' => $user->id]);
        // DBから削除されたユーザーのセクションが削除されているか
        $this->assertDatabaseMissing('sections', ['user_id' => $user->id]);
        // DBから削除されたユーザーのコンテンツが削除されているか
        $this->assertDatabaseMissing('contents', ['user_id' => $user->id]);
        // DBから削除されたユーザーのブログが削除されているか
        $this->assertDatabaseMissing('blogs', ['user_id' => $user->id]);

        // 認証用のセッションデータが削除されているか確認
        $this->assertGuest();
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
        // テスト用のユーザーデータの作成
        $password = Str::random(10);
        $user = User::factory()->state(
            [
            'password' => Hash::make($password)
            ]
        )->create();

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

        // データの更新
        $response = $this->actingAs($user)->put('/user/'.$user->id, $data);
        $response->assertStatus(200);

        // ユーザー用のプロフィールDBが更新されているか確認
        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => $data['newName'], 'email' => $data['newEmail']]);
    }

    /**
     * ユーザー情報の取得のテスト
     *
     * test
     *
     * @return void
     */
    public function show()
    {
        // テスト用のユーザーデータの作成
        $user = User::factory()->create();
        Profile::factory()->state(['icon' => null])->for($user)->create();

        // ユーザーの取得を行うリクエストを送信
        $response = $this->get('/user/'.$user->id);

        // リクエストの結果の確認
        $trueData = [
            'id' => $user->id,
            'name' => $user->name,
            'icon' => null,
        ];
        $response->assertExactJson($trueData, true);
    }
}
