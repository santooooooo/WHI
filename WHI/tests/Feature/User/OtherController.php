<?php
declare(strict_types=1);

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OtherControllller extends TestCase
{
    use RefreshDatabase;
    /**
     * ユーザーログインのテスト
     *
     * @test
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
}
