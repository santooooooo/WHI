<?php

namespace Tests\Unit\User;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use App\Services\User\SignUp;
use App\Services\User\WriteProfile;
use App\Services\User\CreateSection;
use App\Services\User\DeleteSection;

class DeleteSectionTest extends TestCase
{
    use RefreshDatabase;
    /**
     * プロフィールのセクションの削除のテスト
     *
     * test
     * @return void
     */
    public function remove()
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

        // プロフィールのセクションの作成
        $sectionName = 'test';
        $createSection = new CreateSection();
        $createSection->create($id, $name, $sectionName);

        // プロフィールのセクションの削除
        $domain = new DeleteSection();
        $result = $domain->remove($id, $name, $sectionName);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('sections', ['user_id' => $id, 'name' => $sectionName]);
    }

    /**
     * プロフィールのすべてのセクションの削除のテスト
     *
     * @test
     * @return void
     */
    public function allRemove()
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

        // プロフィールのセクションを2件作成
        $sectionName = 'test';
        $createSection = new CreateSection();
        $createSection->create($id, $name, $sectionName);
        $createSection->create($id, $name, $sectionName);

        // プロフィールの全てのセクションの削除
        $domain = new DeleteSection();
        $domain->allRemove($id, $name);

        // プロフィールの全てのセクションの削除できたか確認
        $this->assertDatabaseCount('sections', 0);
    }
}
