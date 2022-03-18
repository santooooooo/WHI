<?php
declare(strict_types=1);

namespace Tests\Unit\User;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\User\SignUp;
use App\Services\User\WriteProfile;
use App\Services\User\CreateSection;
use App\Services\User\CreateBlog;
use App\Services\User\DeleteBlog;

class DeleteBlogTest extends TestCase
{
    use RefreshDatabase;
    /**
     * ブログの削除のテスト
     *
     * test
     *
     * @return void
     */
    public function remove()
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
        $createBlog = new CreateBlog();
        $createBlog->create($userId, $sectionId, $title, $text);

        // ブログの削除
        $domain = new DeleteBlog();
        $domain->remove($userId, $sectionId, $blogId);

        // ブログの削除の確認
        $this->assertDatabaseMissing('blogs', ['user_id' => $userId, 'section_id' => $sectionId, 'title' => $title, 'text' => $text]);
    }

    /**
     * ユーザー削除時にそのユーザーの全てのブログの削除のテスト
     *
     * test
     * @return void
     */
    public function allRemove()
    {
        // 2件のユーザー情報の登録
        $userId = 1;
        $name = 'Jamboo';
        $email = 'Jamboo@gmail.com';
        $password = 'Jamboo';

        $userId2 = 2;
        $name2 = 'test';
        $email2 = 'test@gmail.com';
        $password2 = 'testst';

        $signup = new SignUp();
        $signup->record($name, $email, $password);
        $signup->record($name2, $email2, $password2);

        // ２件のプロフィールの作成
        $writeProfile = new WriteProfile();
        $writeProfile->write($userId, $name);
        $writeProfile->write($userId2, $name2);

        // ２件のプロフィールのセクションの作成
        $sectionId = 1;
        $sectionName = 'test';

        $sectionId2 = 2;
        $sectionName2 = 'test2';

        $createSection = new CreateSection();
        $createSection->create($userId, $name, $sectionName);
        $createSection->create($userId2, $name2, $sectionName2);

        // 2件のブログの作成
        $title = 'blog';
        $text = 'test';
        $createBlog = new CreateBlog();
        $createBlog->create($userId, $sectionId, $title, $text);
        $createBlog->create($userId2, $sectionId2, $title, $text);

        // 退会するユーザーのブログの削除
        $domain = new DeleteBlog();
        $domain->allRemove($userId);

        // 退会したユーザーのブログのみ削除されているか確認
        $this->assertDatabaseMissing('blogs', ['user_id' => $userId]);
        $this->assertDatabaseHas('blogs', ['user_id' => $userId2]);
    }

    /**
     * ユーザー削除時にそのユーザーの全てのブログの削除のテスト
     *
     * @test
     * @return void
     */
    public function allRemoveInSection()
    {
        // ユーザー情報の登録
        $userId = 1;
        $name = 'Jamboo';
        $email = 'Jamboo@gmail.com';
        $password = 'Jamboo';

        $signup = new SignUp();
        $signup->record($name, $email, $password);

        // ２件のプロフィールの作成
        $writeProfile = new WriteProfile();
        $writeProfile->write($userId, $name);

        // ２件のプロフィールのセクションの作成
        $sectionId = 1;
        $sectionName = 'test';

        $sectionId2 = 2;
        $sectionName2 = 'test2';

        $createSection = new CreateSection();
        $createSection->create($userId, $name, $sectionName);
        $createSection->create($userId, $name, $sectionName2);

        // 2件のブログの作成
        $title = 'blog';
        $text = 'test';

        $createBlog = new CreateBlog();
        $createBlog->create($userId, $sectionId, $title, $text);
        $createBlog->create($userId, $sectionId2, $title, $text);
        // 削除するセクションのブログの削除
        $domain = new DeleteBlog();
        $domain->allRemoveInSection($userId, $sectionId);

        // 退会したユーザーのブログのみ削除されているか確認
        $this->assertDatabaseMissing('blogs', ['section_id' => $sectionId]);
        $this->assertDatabaseHas('blogs', ['section_id' => $sectionId2]);
    }
}
