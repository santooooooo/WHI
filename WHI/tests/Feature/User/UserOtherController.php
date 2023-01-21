<?php
declare(strict_types=1);

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Services\Auth\SetResetPasswordAuth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserOtherController extends TestCase
{
    use RefreshDatabase;
    /**
     * ユーザーログインのテスト
     *
     * test
     *
     * @return void
     */
    public function login()
    {
        // テスト用のユーザーデータの作成
        $password = Str::random(10);
        $user = User::factory()->state(
            [
            'password' => Hash::make($password)
            ]
        )->create();

        // 誤ったユーザー情報の使用
        //$falseEmail = 'testssssss@gmail.com';
        //$falsePassword = 'w44wwwwwddddd';

        $response = $this->post('/login', ['email' => $user->email, 'password' => $password]);

        $data = ['id' => $user->id, 'name' => $user->name];
        $response->assertStatus(200);
        $response->assertJson($data);
        $this->assertAuthenticated();
    }

    /**
     * ユーザーlogoutのテスト
     *
     * @test
     *
     * @return void
     */
    public function logout()
    {
        // ユーザーの作成
        $user = User::factory()->create();

        // ログアウト
        $response = $this->actingAs($user)->post('/logout');

        $response->assertStatus(200);
        $data = ['ログアウトしました。'];
        $response->assertExactJson($data, true);
        $this->assertGuest();
    }

    /**
     * OGP取得のテスト
     *
     * test
     *
     * @return void
     */
    public function ogp()
    {
        // 外部サイトのOGPの取得
        $url = 'https://qiita.com/';
        $response = $this->post('/ogp', ['url' => $url]);

        // リクエストの成功及びレスポンスの値の確認
        $response->assertStatus(200);
        $trueResult = [
        'title' => 'エンジニアに関する知識を記録・共有するためのサービス - Qiita',
        'description' => 'Qiitaは、エンジニアに関する知識を記録・共有するためのサービスです。 プログラミングに関するTips、ノウハウ、メモを簡単に記録 &amp; 公開することができます。',
        'image' => 'https://cdn.qiita.com/assets/qiita-ogp-3b6fcfdd74755a85107071ffc3155898.png',
        'url' => 'https://qiita.com/',
        'favicon' => 'https://cdn.qiita.com/assets/favicons/public/production-c620d3e403342b1022967ba5e3db1aaa.ico'
        ];
        $response->assertExactJson($trueResult, true);
    }

    /**
     * パスワードの再設定用のIDの確認のテスト
     *
     * test
     *
     * @return void
     */
    public function checkId()
    {
        // idの作成
        $email = 'santo.shunsuke@gmail.com';
        $auth = new SetResetPasswordAuth();
        $identification = $auth->set($email);

        // idの確認
        $response = $this->post('/checkId', ['id' => $identification]);

        // リクエストの成功及びレスポンスの値の確認
        $response->assertStatus(200);
        $response->assertExactJson([$email], true);
    }

    /**
     * パスワードの再設定のテスト
     *
     * test
     *
     * @return void
     */
    public function resetPassword()
    {
        // ユーザー情報
        $id = 1;
        $name = 'test';
        $email = 'test@gmail.com';
        $password = '@%!$w44wwwww';

        // ユーザーの登録を行うリクエストを送信
        $this->post('/user', ['name' => $name, 'email' => $email, 'password' => $password]);

        // パスワード再設定を行うリクエストを送信
        $updatePassword = 'w44wwwww@%!$';
        $response = $this->post('/resetPassword', ['email' => $email, 'password' => $updatePassword]);

        // リクエストの成功及びレスポンスの値の確認
        $response->assertStatus(200);
        $response->assertExactJson(['Success'], true);
        // パスワードが更新されたか確認
        $user = User::find($id);
        $this->assertTrue(Hash::check($updatePassword, $user->password));
    }
}
