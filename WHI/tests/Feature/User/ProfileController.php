<?php
declare(strict_types=1);
namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class ProfileController extends TestCase
{
    use RefreshDatabase;
    /**
     * ユーザーのプロフィールの新規作成
     *
     * @test
     * @return void
     */
    public function store()
    {
        // ユーザー情報
        $name = 'test';
        $email = 'test@gmail.com';
        $password = 'w44wwwww';

        // ユーザーの登録を行うリクエストを送信
        $response = $this->post('/user', ['name' => $name, 'email' => $email, 'password' => $password]);

        $this->assertDatabaseHas('profiles', ['user_id' => 1, 'career' => null]);

        $data = [
        'name' => $name,
        'icon' => UploadedFile::fake()->image('fake.png')->size(10000),
        // 画像以外のファイル
        //'icon' => UploadedFile::fake()->image('fake.png.js'),
        'career' => Str::random(1000),
        'title' => Str::random(255),
        'text' => Str::random(10000),
        'mail' => $email,
        'twitter' => 'Jamboo',
        ];
        $response = $this->put('/profile/1', $data);

        // リクエストが正常に実行されているかチェック
        $response->assertStatus(200);

        // プロフィールの保存が実行されているかチェック
        $result = ['Success!'];
        $response->assertExactJson($result, true);

        // データベースに登録されているかのチェック
        $this->assertDatabaseCount('profiles', 1);
        $this->assertDatabaseHas('profiles', ['user_id' => 1, 'career' => $data['career']]);
    }
}
