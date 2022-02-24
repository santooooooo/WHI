<?php
declare(strict_types=1);

namespace Tests\Unit\User;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\User\SignUp;
use App\Services\User\WriteProfile;
use App\Services\User\CreateSection;
use App\Services\User\UpdateSection;

class UpdateSectionTest extends TestCase
{
    use RefreshDatabase;
    /**
     * プロフィールのセクションの更新のテスト
     *
     * @test
     *
     * @return void
     */
    public function update()
    {
        // ユーザー情報
        $id = 1;
        $name = 'Jamboo';
        $email = 'Jamboo@gmail.com';
        $password = 'Jamboo';

        // ユーザー情報の登録
        $signup = new SignUp();
        $signup->record($name, $email, $password);

        // プロフィールの作成
        $writeProfile = new WriteProfile();
        $writeProfile->write($id, $name);

        // プロフィールの新たなセクションの作成
        $sectionName = 'test';
        $createSection = new CreateSection();
        $createSection->create($id, $name, $sectionName);

        // プロフィールの既存のセクションの更新
        $newSectionName = "Jamboo!";
        $domain = new UpdateSection();
        $result = $domain->update($id, $name, $sectionName, $newSectionName);

        // 結果の確認
        $this->assertTrue($result);
        $this->assertDatabaseHas('sections', ['user_id' => $id, 'name' => $newSectionName]);
    }
}
