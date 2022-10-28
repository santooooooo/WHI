<?php
declare(strict_types=1);

namespace Tests\Unit\User;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\User\GetUser;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Services\User\WriteProfile;
use App\Services\User\SignUp;
use App\Services\User\DeleteProfile;

class GetUserTest extends TestCase
{
    use RefreshDatabase;
    /**
     * ユーザー情報の取得のテスト
     *
     * @test
     * @return void
     */
    public function getInfo()
    {
        // ユーザー情報
        $id = 1;
        $name = 'Jamboo';
        $email = 'Jamboo@gmail.com';
        $password = 'Jamboo';

        // ユーザー情報の登録
        $signUp = new SignUp();
        $signUp->record($name, $email, $password);

        //新規ユーザー用のプロフィールDBの作成
        $writeProfile = new WriteProfile();
        $writeProfile->write($id, $name);
 
        $this->assertDatabaseHas('profiles', ['user_id' => $id, 'icon' => null]);
        // プロフィール情報
        $data = [
        'icon' => UploadedFile::fake()->image('fake.png'),
        'career' => Str::random(10000),
        'title' => Str::random(255),
        'text' => Str::random(10000),
        'mail' => 'Jamboo@gmail.com',
        'twitter' => 'Jamboo'
        ];

        $writeProfile->write($id, $name, $data['icon'], $data['career'], $data['title'], $data['text'], $data['mail'], $data['twitter']);

        // ユーザーのプロフィールの取得
        $domain = new GetUser();
        $result = $domain->getInfo($id);

        // ユーザーのプロフィールが取得できているのかチェック
        $icon = DB::table('profiles')->where('user_id', $id)->value('icon');
        $this->assertSame('https://whi.s3.amazonaws.com/'.$icon, $result['icon']);
        $this->assertSame($id, $result['id']);
        $this->assertSame($name, $result['name']);

        // テスト用の画像の消去
        $deleteProfile = new DeleteProfile();
        $deleteProfile->deleteProfile($id, $name);
    }
}
