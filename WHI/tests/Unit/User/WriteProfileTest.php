<?php
declare(strict_types=1);

namespace Tests\Unit\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Services\User\WriteProfile;
use App\Services\User\SignUp;
use App\Services\User\DeleteProfile;
use App\Models\Profile;
use App\Models\User;

class WriteProfileTest extends TestCase
{
    use RefreshDatabase;
    /**
     * 新たなプロフィールの作成
     *
     * @test
     *
     * @return void
     */
    public function create()
    {
        // ユーザー情報
        $name = 'Jamboo';
        $email = 'Jamboo@gmail.com';
        $password = 'Jamboo';

        // ユーザー情報の登録
        $domain = new SignUp();
        $domain->record($name, $email, $password);

        $domain = new WriteProfile();
        $domain->write(1, $name);
        $this->assertDatabaseHas('profiles', ['user_id' => 1, 'career' => null]);
    }

    /**
     * プロフィールの更新
     *
     * @test
     * @return void
     */
    public function update()
    {
        //データベースの初期化
        DB::table('users')->truncate();

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
 
        $this->assertDatabaseHas('profiles', ['user_id' => $id, 'career' => null]);
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

        $result = $domain->write($id, $name, $data['icon'], $data['career'], $data['title'], $data['text'], $data['mail'], $data['twitter']);

        // すでにアイコンがある状態でアイコンを更新
        //$data['icon'] = UploadedFile::fake()->image('fake2.png');
        //$result = $domain->write($id, $name, $data['icon'], $data['career'], $data['title'], $data['text'], $data['mail'], $data['twitter']);

	// 再度実行し、一回目で保存した画像がAmazonS3から消去されているか確認
        //$result = $domain->write($id, $name, $data['icon'], $data['career'], $data['title'], $data['text'], $data['mail'], $data['twitter']);

        // 実行結果
        $this->assertTrue($result);

        // データベースに登録されているかのチェック
        $this->assertDatabaseHas('profiles', ['user_id' => $id, 'career' => $data['career']]);

        // テスト用の画像の消去
        $deleteProfile = new DeleteProfile();
        $deleteProfile->deleteProfile($id, $name);
    }
}
