<?php
declare(strict_types=1);

namespace Tests\Unit\User;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\User\SignUp;
use App\Services\User\WriteProfile;
use App\Services\User\CreateSection;
use App\Services\User\GetSection;

class GetSectionTest extends TestCase
{
    use RefreshDatabase;
    /**
     * プロフィールのすべてのセクション名の取得のテスト
     *
     * @test
     * @return void
     */
    public function index()
    {
        // ユーザー情報
        $id = 1;
        $name = 'Jamboo';
        $email = 'Jamboo@gmail.com';
        $password = 'Jamboo';

        // ユーザー情報の登録
        $signup = new SignUp();
        $signup->record($name, $email, $password);

        $writeProfile = new WriteProfile();
        $writeProfile->write($id, $name);

        // プロフィールのセクションを二つ作成
        $sectionName = 'test';
        $createSection = new CreateSection();
        $createSection->create($id, $name, $sectionName);
        $createSection->create($id, $name, $sectionName);
 
        $data = [$sectionName, $sectionName];

        $domain = new GetSection($id);
        $result = $domain->index();
        $this->assertTrue($data === $result);
    }
}
