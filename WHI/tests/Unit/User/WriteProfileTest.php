<?php

namespace Tests\Unit\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Services\User\WriteProfile;
use App\Services\User\SignUp;
use App\Models\Profile;
use App\Models\User;

class WriteProfileTest extends TestCase
{
    use RefreshDatabase;
    /**
     * 新たなプロフィールの作成
     *
     * test
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
        $result = $domain->write(1, $name);
        $this->assertTrue($result);
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

        // テスト用の画像の生成の準備
        Storage::fake('local');

        // ユーザー情報
        $name = 'Jamboo';
        $email = 'Jamboo@gmail.com';
        $password = 'Jamboo';

        // ユーザー情報の登録
        $domain = new SignUp();
        $domain->record($name, $email, $password);

        $domain = new WriteProfile();
        $domain->write(1, $name);
 
        // プロフィール情報
        $data = [
        'user_id' => 1,
        'icon' => UploadedFile::fake()->image('fake.png'),
        'career' => Str::random(10000),
        'title' => Str::random(255),
        'text' => Str::random(10000),
        'mail' => 'Jamboo@gmail.com',
        'twitter' => 'Jamboo'
        ];

        $result = $domain->write($data['user_id'], $name, $data['icon'], $data['career'], $data['title'], $data['text'], $data['mail'], $data['twitter']);

        $this->assertTrue($result);
        $this->assertDatabaseCount('profiles', 1);
    }
}
