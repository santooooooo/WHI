<?php
declare(strict_types=1);

namespace Tests\Unit\User;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\User\SignUp;
use App\Services\User\WriteProfile;
use App\Services\User\CreateSection;
use App\Services\User\CreateBlog;
use App\Services\User\GetBlog;
use App\Models\Blog;
use Carbon\Carbon;

class GetBlogTest extends TestCase
{
    use RefreshDatabase;
    /**
     * コンテンツブログの取得のテスト
     *
     * @test
     * @return void
     */
    public function index()
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

        // ブログの取得
        $domain = new GetBlog();
        $result = $domain->index($blogId);

        // ブログの取得できているか確認
        $db = new Blog();
        $blog = $db->where('id', $blogId)->first();
	$updated = (new Carbon($blog->updated_at))->toDateTimeString();
        $trueResult = [
        'id' => $blogId,
        'title' => $title,
        'text' => $text,
        'updated' => $updated,
        ];
        $this->assertSame($result, $trueResult);
    }
}
