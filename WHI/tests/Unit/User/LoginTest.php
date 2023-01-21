<?php
declare(strict_types=1);

namespace Tests\Unit\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Services\User\Login;
use App\Services\User\SignUp;
use Database\Seeders\UserSeeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    /**
     * ログイン機能のテスト
     *
     * @test
     * @return void
     */
    public function execute()
    {
        $password = Str::random(10);
        $user = User::factory()->state(
            [
            'password' => Hash::make($password)
            ]
        )->create();

        // 誤ったデータの入力
        //$falseEmail = 'Jamboooooo@gmail.com';
        //$falsePassword = 'Jamboo00';

        $domain = new Login();
        $test = $domain->execute($user->email, $password);
        $data = ['id' => $user->id, 'name' => $user->name];

        $this->assertDatabaseCount('users', 1);
        $this->assertTrue($data === $test);
    }
}
