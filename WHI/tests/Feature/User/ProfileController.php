<?php
declare(strict_types=1);
namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProfileController extends TestCase
{
    use RefreshDatabase;
    /**
     * ユーザーのプロフィールの更新
     *
     * @test
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
        $response = $this->post('/user', ['name' => $name, 'email' => $email, 'password' => $password]);

        $this->assertDatabaseHas('profiles', ['user_id' => $id, 'career' => null]);

        $data = [
        'name' => $name,
        'icon' => UploadedFile::fake()->image('fake.png')->size(10000),
        // 画像以外のファイル
        //'icon' => UploadedFile::fake()->image('fake.png.js'),
        'career' => Str::random(1000),
        'title' => Str::random(255),
        'text' => Str::random(10000),
        'email' => $email,
        'twitter' => 'Jamboo',
        ];
        $response = $this->put('/profile/'.$id, $data);

        // リクエストが正常に実行されているかチェック
        $response->assertStatus(200);

        // プロフィールの保存が実行されているかチェック
        $result = ['Success!'];
        $response->assertExactJson($result, true);

        // データベースに登録されているかのチェック
        $this->assertDatabaseCount('profiles', 1);
        $this->assertDatabaseHas('profiles', ['user_id' => $id, 'career' => $data['career'], 'mail' => $data['email']]);

        // ユーザーの退会を行うリクエストを送信
        $response = $this->delete('/user/'.$id, ['name' => $name]);
    }

    /**
     * ユーザーのプロフィール表示
     *
     * test
     * @return void
     */
    public function index()
    {
        // ユーザー情報
        $id = 1;
        $name = 'test';
        $email = 'test@gmail.com';
        $password = 'w44wwwww';

        // ユーザーの登録を行うリクエストを送信
        $this->post('/user', ['name' => $name, 'email' => $email, 'password' => $password]);

        $data = [
        'name' => $name,
        'icon' => UploadedFile::fake()->image('fake.jpg')->size(10000),
        //'icon' => null,
        'career' => Str::random(1000),
        'title' => Str::random(255),
        'text' => Str::random(10000),
        'mail' => $email,
        'twitter' => 'Jamboo',
        ];
        // プロフィールの更新を行うリクエストを送信
        $response = $this->put('/profile/'.$id, $data);
        $response->assertStatus(200);

        // プロフィールの取得を行うリクエストを送信
        $response = $this->get('/user/'.$id.'/profile');

        // ユーザーのプロフィールが取得できているかチェック
        $icon = DB::table('profiles')->where('user_id', $id)->value('icon');
        $result = [
        'icon' => 'https://whi.s3.amazonaws.com/'.$icon,
        'career' => $data['career'],
        'title' => $data['title'],
        'text' => $data['text'],
        'mail' => $data['mail'],
        'twitter' => $data['twitter'],
        ];
        $response->assertExactJson($result);

        // ユーザーの退会を行うリクエストを送信
        $response = $this->delete('/user/'.$id, ['name' => $name]);
    }

    /**
     * ユーザーのプロフィールアイコンのみの削除
     *
     * test
     *
     * @return void
     */
    public function destroy()
    {
        // ユーザー情報
        $id = 1;
        $name = 'test';
        $email = 'test@gmail.com';
        $password = 'w44wwwww';

        // ユーザーの登録を行うリクエストを送信
        $this->post('/user', ['name' => $name, 'email' => $email, 'password' => $password]);

        $data = [
        'name' => $name,
        'icon' => UploadedFile::fake()->image('fake.jpg')->size(10000),
        //'icon' => null,
        'career' => Str::random(1000),
        'title' => Str::random(255),
        'text' => Str::random(10000),
        'mail' => $email,
        'twitter' => 'Jamboo',
        ];
        // プロフィールの更新を行うリクエストを送信
        $this->put('/profile/'.$id, $data);

        // ユーザーのプロフィールのアイコンのパスの取得
        $icon = DB::table('profiles')->where('user_id', $id)->value('icon');

        // プロフィールアイコンのみの削除を行うリクエストの送信
        $response = $this->delete('/profile/'.$id, ['name' => $name]);
        $response->assertStatus(200);

        // データベースに登録されているプロフィールアイコンのパスが削除されているかチェック
        $this->assertDatabaseHas('profiles', ['user_id' => $id, 'icon' => null]);

        // AmazonS3からアイコンが削除されているかチェック
        $this->assertTrue(Storage::disk('s3')->missing($icon));

        // ユーザーの退会を行うリクエストを送信
        $response = $this->delete('/user/'.$id, ['name' => $name]);
    }
}
