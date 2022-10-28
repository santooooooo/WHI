<?php
declare(strict_types=1);

namespace Tests\Unit\User;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\User\SignUp;
use App\Services\User\WriteProfile;
use App\Services\User\CreateSection;
use App\Services\User\CreateContent;

class CreateContentTest extends TestCase
{
    use RefreshDatabase;
    /**
     * プロフィールのコンテンツの保存のテスト.
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

        // プロフィールのコンテンツの作成
        $contentId = 1;
        $type = 'blog';
        $substance = 'test';
        $domain = new CreateContent();
        $result = $domain->create($userId, $sectionId, $type, $substance);

        // 実行後に返されるデータの確認とDBに保存されているかのチェック
        $trueData = [
                'id' => $contentId,
                'section_id' => $sectionId,
                'type' => $type,
                'substance' => $substance,
        ];
        $this->assertTrue($result === $trueData);
        $this->assertDatabaseHas('contents', ['user_id' => $userId, 'section_id' => $sectionId, 'type' => $type, 'substance' => $substance]);
    }
}
