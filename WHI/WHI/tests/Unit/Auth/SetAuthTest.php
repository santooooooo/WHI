<?php
declare(strict_types=1);

namespace Tests\Unit\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Services\Auth\SetAuth;

class SetAuthTest extends TestCase
{
    use RefreshDatabase;
    /**
     * リクエストの認証情報の保存のテスト
     *
     * @test
     * @return void
     */
    public function set()
    {
        $id = 1;
        $email = 'test@test.com';

        $domain = new SetAuth();
        $domain->set($email);
        $data = [
        'id' => $id,
        'email' => $email,
        ];
        $this->assertDatabaseHas('auths', $data);
    }
}
