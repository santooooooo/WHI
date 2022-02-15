<?php
declare(strict_types=1);

namespace Tests\Unit\User;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\User\GetProfile;
use Illuminate\Support\Str;
use App\Services\User\WriteProfile;
use App\Services\User\SignUp;
use App\Services\User\DeleteProfile;

class GetProfileTest extends TestCase
{
    use RefreshDatabase;
    /**
     * ユーザーのプロフィールの取得のテスト
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
        $domain = new SignUp();
        $domain->record($name, $email, $password);

        //新規ユーザー用のプロフィールDBの作成
        $domain = new WriteProfile();
        $domain->write($id, $name);
 
        $this->assertDatabaseHas('profiles', ['user_id' => $id, 'icon' => null]);
        // プロフィール情報
        $data = [
        'icon' => UploadedFile::fake()->image('fake.png'),
        // ファイルじゃない情報
        //'icon' => '/var/www/public/',
        'career' => Str::random(10000),
        'title' => Str::random(255),
        'text' => Str::random(10000),
        'mail' => 'Jamboo@gmail.com',
        'twitter' => 'Jamboo'
        ];

        $domain->write($id, $name, $data['icon'], $data['career'], $data['title'], $data['text'], $data['mail'], $data['twitter']);

        // ユーザーのプロフィールの取得
        $domain = new GetProfile();
        $result = $domain->getInfo($id);

        // ユーザーのプロフィールが取得できているのかチェック
        $this->assertArrayHasKey('icon', $result);
        $this->assertArrayHasKey('career', $result);
        $this->assertArrayHasKey('title', $result);
        $this->assertArrayHasKey('text', $result);
        $this->assertArrayHasKey('mail', $result);
        $this->assertArrayHasKey('twitter', $result);

        // テスト用の画像の消去
        $deleteProfile = new DeleteProfile();
        $deleteProfile->deleteProfile($id, $name);
    }
}
