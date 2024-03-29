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
use App\Models\User;
use App\Models\Profile;

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
        // ユーザー情報の作成と初期プロフィールの作成
        $user = User::factory()->create();
        Profile::factory()->for($user)->create();

        $data = [
        'icon' => UploadedFile::fake()->image('fake.png')->size(10000),
        // 画像以外のファイル
        //'icon' => UploadedFile::fake()->image('fake.png.js'),
        'career' => Str::random(1000),
        'title' => Str::random(255),
        'text' => Str::random(10000),
        'email' => $user->email,
        'twitter' => 'Jamboo',
        ];
        $response = $this->actingAs($user)->put('/profile/'.$user->id, $data);

        // リクエストが正常に実行されているかチェック
        $response->assertStatus(200);

        // プロフィールの保存が実行されているかチェック
        $result = ['Success!'];
        $response->assertExactJson($result, true);

        // データベースに登録されているかのチェック
        $this->assertDatabaseHas('profiles', ['user_id' => $user->id, 'career' => $data['career'], 'mail' => $data['email']]);

        // テスト用のファイルをS3から削除するためにユーザーの退会を行うリクエストを送信
        $this->delete('/user/'.$user->id, ['name' => $user->name]);
    }

    /**
     * ユーザーのプロフィール表示
     *
     * test
     * @return void
     */
    public function index()
    {
        // ユーザー情報の作成と初期プロフィールの作成
        $user = User::factory()->create();
        Profile::factory()->for($user)->create();


        $data = [
        'name' => $user->name,
        'icon' => UploadedFile::fake()->image('fake.jpg')->size(10000),
        'career' => Str::random(1000),
        'title' => Str::random(255),
        'text' => Str::random(10000),
        'email' => $user->email,
        'twitter' => 'Jamboo',
        ];
        // プロフィールの更新を行うリクエストを送信
        $response = $this->actingAs($user)->put('/profile/'.$user->id, $data);
        $response->assertStatus(200);

        // プロフィールの取得を行うリクエストを送信
        $response = $this->get('/user/'.$user->id.'/profile');

        // ユーザーのプロフィールが取得できているかチェック
        $icon = DB::table('profiles')->where('user_id', $user->id)->value('icon');
        $result = [
        'icon' => 'https://whi-local.s3.amazonaws.com/'.$icon,
        'career' => $data['career'],
        'title' => $data['title'],
        'text' => $data['text'],
        'mail' => $data['email'],
        'twitter' => $data['twitter'],
        ];
        $response->assertExactJson($result);

        // テスト用のファイルをS3から削除するためにユーザーの退会を行うリクエストを送信
        $this->delete('/user/'.$user->id, ['name' => $user->name]);
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
        // ユーザー情報の作成と初期プロフィールの作成
        $user = User::factory()->create();
        Profile::factory()->for($user)->create();

        // プロフィールアイコンのみの削除を行うリクエストの送信
        $response = $this->actingAs($user)->delete('/profile/'.$user->id, ['name' => $user->name]);
        $response->assertStatus(200);

        // データベースに登録されているプロフィールアイコンのパスが削除されているかチェック
        $this->assertDatabaseHas('profiles', ['user_id' => $user->id, 'icon' => null]);

        // ユーザーの退会を行うリクエストを送信
        $response = $this->delete('/user/'.$user->id, ['name' => $user->name]);
    }
}
