<?php

namespace Tests\Unit\User;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use App\Services\User\SignUp;
use App\Services\User\WriteProfile;
use App\Services\User\CreateSection;
use App\Services\User\CreateContent;
use App\Services\User\DeleteContent;

class DeleteContentTest extends TestCase
{
    use RefreshDatabase;
    /**
     * プロフィールのコンテンツの削除のテスト
     *
     * @test
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

        // プロフィールのコンテンツの作成
        $contentId = 1;
        $type = 'blog';
        $substance = 'test';
        $createContent = new CreateContent();
        $createContent->create($userId, $sectionId, $type, $substance);

        $this->assertDatabaseHas('contents', ['user_id' => $userId, 'section_id' => $sectionId, 'type' => $type, 'substance' => $substance]);

        // プロフィールのコンテンツの削除
        $domain = new DeleteContent();
        $domain->remove($userId, $sectionId, $contentId);

        $this->assertDatabaseMissing('contents', ['user_id' => $userId, 'section_id' => $sectionId, 'type' => $type, 'substance' => $substance]);
    }
}
