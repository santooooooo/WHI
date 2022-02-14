<?php
declare(strict_types=1);

namespace Tests\Unit\User;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Services\User\WriteProfile;
use App\Services\User\SignUp;
use App\Services\User\DeleteProfile;
use App\Models\Profile;

class DeleteProfileTest extends TestCase
{
    use RefreshDatabase;
    /**
     * ユーザーのプロフィールの削除
     *
     * @test
     * @return void
     */
    public function deleteProfile()
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
 
        $this->assertDatabaseHas('profiles', ['user_id' => 1, 'career' => null]);
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

        $result = $writeProfile->write($id, $name, $data['icon'], $data['career'], $data['title'], $data['text'], $data['mail'], $data['twitter']);

        // 実行結果
        $this->assertTrue($result);

        // データベースに登録されているかのチェック
        $this->assertDatabaseHas('profiles', ['user_id' => $id, 'career' => $data['career']]);

        // ユーザーのプロフィールのアイコンのパスの取得
        $icon = Profile::where('user_id', $id)->value('icon');

        // ユーザーのプロフィールの削除
        $domain = new DeleteProfile();
        $domain->deleteProfile($id, $name);

        // 退会するユーザーのプロフィールが削除されているかのチェック
        $this->assertDatabaseMissing('profiles', ['user_id' => $id, 'career' => $data['career']]);

        // AmazonS3からアイコンが削除されているかチェック
        $this->assertTrue(Storage::disk('s3')->missing($icon));
    }
}
