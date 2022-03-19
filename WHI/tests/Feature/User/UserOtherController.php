<?php
declare(strict_types=1);

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserOtherController extends TestCase
{
    use RefreshDatabase;
    /**
     * ユーザーログインのテスト
     *
     * test
     * @return void
     */
    public function login()
    {
        // ユーザー情報
        $name = 'test';
        $email = 'test@gmail.com';
        $password = 'w44wwwww';

        // ユーザーの登録を行うリクエストを送信
        $response = $this->post('/user', ['name' => $name, 'email' => $email, 'password' => $password]);

        // 誤ったユーザー情報の使用
        //$falseEmail = 'testssssss@gmail.com';
        //$falsePassword = 'w44wwwwwddddd';

        $response = $this->post('/login', ['email' => $email, 'password' => $password]);

        $data = ['id' => 1, 'name' => $name];
        $response->assertStatus(200);
        $response->assertJson($data);
    }

    /**
     * ユーザーログインのテスト
     *
     * @test
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
        'url' => 'https://qiita.com/'
        ];
        $response->assertExactJson($trueResult, true);
    }
}
