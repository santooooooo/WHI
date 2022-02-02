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
        $user = User::find(1);

        // データベースの情報が元の情報と同じであることを確認
        $result = $name === $user->name && $email === $user->email && Hash::check($password, $user->password);

        $response->assertStatus(200);
        $this->assertTrue($result);

        // 返信データが元のユーザーの情報と一致しているか確認
        $data = [$name, $email];
        $response->assertJson($data);
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
        $name = 'test';
        $email = 'test@gmail.com';
        $password = 'w44wwwww';

        // ユーザーの登録を行うリクエストを送信
        $this->post('/user', ['name' => $name, 'email' => $email, 'password' => $password]);

        $user = User::find(1);

        // ユーザーの退会を行うリクエストを送信
        $response = $this->delete('/user/1', ['email' => $email]);

        // 存在しないユーザーの退会を行うリクエストを送信
        //$response = $this->delete('/user/2', ['email' => $email]);

        $response->assertStatus(200);
        $this->assertDeleted($user);
    }
}
