<?php
declare(strict_types=1);

namespace Tests\Unit\User;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\User\SignUp;
use App\Services\User\WriteProfile;
use App\Services\User\CreateSection;
use App\Services\User\CreateBlog;

class CreateBlogTest extends TestCase
{
    use RefreshDatabase;
    /**
     * コンテンツブログの保存のテスト
     *
     * @test
     * @return void
     */
    public function create()
    {
        // ユーザー情報
        $userId = 1;
        $name = 'Jamboo';
        $email = 'Jamboo@gmail.com';
        $password = 'Jamboo';

        // ユーザー情報の登録
        $signup = new SignUp();
        $signup->record($name, $email, $password);

        // プロフィールの作成
        $writeProfile = new WriteProfile();
        $writeProfile->write($userId, $name);

        // プロフィールのセクションの作成
        $sectionId = 1;
        $sectionName = 'test';
        $createSection = new CreateSection();
        $createSection->create($userId, $name, $sectionName);

        // ブログの作成
        $blogId = 1;
        $title = 'blog';
        $text = 'test';
        $domain = new CreateBlog();
        $result = $domain->create($userId, $sectionId, $title, $text);

        // 作成されたブログの確認
        $this->assertSame($result, $blogId);
        $this->assertDatabaseHas('blogs', ['user_id' => $userId, 'section_id' => $sectionId, 'title' => $title, 'text' => $text]);
    }
}
