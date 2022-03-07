<?php
declare(strict_types=1);

namespace Tests\Unit\User;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\User\SignUp;
use App\Services\User\WriteProfile;
use App\Services\User\CreateSection;

class CreateSectionTest extends TestCase
{
    use RefreshDatabase;
    /**
     * プロフィールのセクションの作成のテスト
     *
     * @test
     * @return void
     */
    public function create()
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
        $domain = new CreateSection();
        $result = $domain->create($id, $name, $sectionName);
        $this->assertTrue($result === $sectionName);

        $this->assertDatabaseHas('sections', ['user_id' => $id, 'name' => $sectionName]);
    }
}
