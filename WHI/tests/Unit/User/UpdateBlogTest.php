<?php

namespace Tests\Unit\User;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\User\SignUp;
use App\Services\User\WriteProfile;
use App\Services\User\CreateSection;
use App\Services\User\CreateBlog;
use App\Services\User\UpdateBlog;

class UpdateBlogTest extends TestCase
{
    use RefreshDatabase;
    /**
     * コンテンツブログの更新
     *
     * @test
     * @return void
     */
    public function update()
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
        $domain->create($userId, $sectionId, $title, $text);

        // ブログの更新
        $updateTitle = 'Jamboo Blog';
        $updateText = 'Jamboo!!';
        $domain = new UpdateBlog();
        $result = $domain->update($userId, $blogId, $updateTitle, $updateText);

        // ブログの更新の確認
        $this->assertTrue($result);
        $this->assertDatabaseHas('blogs', ['user_id' => $userId, 'section_id' => $sectionId, 'title' => $updateTitle, 'text' => $updateText]);
    }
}
