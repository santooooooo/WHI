<?php
declare(strict_types=1);

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;

class BlogController extends TestCase
{
    use RefreshDatabase;
    /**
     * ブログの作成のテスト
     *
     * test
     *
     * @return void
     */
    public function store()
    {
        // ユーザー情報
        $userId = 1;
        $name = 'test';
        $email = 'test@gmail.com';
        $password = 'w44wwwww';

        // ユーザーの登録を行うリクエストを送信
        $this->post('/user', ['name' => $name, 'email' => $email, 'password' => $password]);

        // プロフィールのセクションの作成
        $sectionId = 1;
        $data = [
        'userName' => $name,
        'sectionName' => 'test'
        ];
        $this->post('/user/'.$userId.'/sections', $data);

        // ブログの作成
        $blogId = 1;
        $blogData = [
        'userId' => $userId,
        'sectionId' => $sectionId,
        'title' => Str::random(255),
        'text' => Str::random(10000)
        ];
        $response = $this->post('/blog/', $blogData);

        // リクエストの結果の確認
        $trueResult = ['Success'];
        $response->assertExactJson($trueResult, true);
        $response->assertStatus(200);

        // 作成されたブログの確認
        $this->assertDatabaseHas('blogs', ['user_id' => $userId, 'section_id' => $sectionId, 'title' => $blogData['title'], 'text' => $blogData['text']]);

        // 作成されたコンテンツの確認
        $appUrl = env('APP_URL');
        $blogUrl = $appUrl.'/#/blogs/'.$blogId;
        $content = ['section_id' => $sectionId, 'type' => 'blog', 'substance' => $blogUrl];

        $this->assertDatabaseHas('contents', $content);
    }

    /**
     * ブログの削除のテスト
     *
     * @test
     *
     * @return void
     */
    public function destroy()
    {
        // ユーザー情報
        $userId = 1;
        $name = 'test';
        $email = 'test@gmail.com';
        $password = 'w44wwwww';

        // ユーザーの登録を行うリクエストを送信
        $this->post('/user', ['name' => $name, 'email' => $email, 'password' => $password]);

        // プロフィールのセクションの作成
        $sectionId = 1;
        $data = [
        'userName' => $name,
        'sectionName' => 'test'
        ];
        $this->post('/user/'.$userId.'/sections', $data);

        // ブログの作成
        $blogId = 1;
        $blogData = [
        'userId' => $userId,
        'sectionId' => $sectionId,
        'title' => 'test',
        'text' => Str::random(10000)
        ];
        $this->post('/blog/', $blogData);

        // ブログの削除
        $deleteBlogData = [
        'userId' => $userId,
        'sectionId' => $sectionId,
        ];
        $response = $this->delete('/blog/'.$blogId, $deleteBlogData);

        // リクエストの結果の確認
        $response->assertStatus(200);
        $trueResult = ['Success'];
        $response->assertExactJson($trueResult, true);

        // ブログが削除されているか確認
        $this->assertDatabaseMissing('blogs', ['user_id' => $userId, 'section_id' => $sectionId, 'title' => $blogData['title'], 'text' => $blogData['text']]);

        // コンテンツが削除されているか確認
        $appUrl = env('APP_URL');
        $blogUrl = $appUrl.'/#/blogs/'.$blogId;
        $content = ['section_id' => $sectionId, 'type' => 'blog', 'substance' => $blogUrl];
        $this->assertDatabaseMissing('contents', $content);
    }

    /**
     * ブログの更新のテスト
     *
     * test
     *
     * @return void
     */
    public function update()
    {
        // ユーザー情報
        $userId = 1;
        $name = 'test';
        $email = 'test@gmail.com';
        $password = 'w44wwwww';

        // ユーザーの登録を行うリクエストを送信
        $this->post('/user', ['name' => $name, 'email' => $email, 'password' => $password]);

        // プロフィールのセクションの作成
        $sectionId = 1;
        $data = [
        'userName' => $name,
        'sectionName' => 'test'
        ];
        $this->post('/user/'.$userId.'/sections', $data);

        // ブログの作成
        $blogId = 1;
        $blogData = [
        'userId' => $userId,
        'sectionId' => $sectionId,
        'title' => 'test',
        'text' => Str::random(10000)
        ];
        $this->post('/blog/', $blogData);

        // ブログの更新
        $updateBlogData = [
        'userId' => $userId,
        'title' => Str::random(255),
        'text' => Str::random(10000)
        ];
        $response = $this->put('/blog/'.$blogId, $updateBlogData);

        // リクエストの結果の確認
        $response->assertStatus(200);
        $trueResult = ['Success'];
        $response->assertExactJson($trueResult, true);

        // 更新されたブログの確認
        $this->assertDatabaseHas('blogs', ['user_id' => $userId, 'section_id' => $sectionId, 'title' => $updateBlogData['title'], 'text' => $updateBlogData['text']]);
    }

    /**
     * ブログの取得のテスト
     *
     * test
     * @return void
     */
    public function show()
    {
        // ユーザー情報
        $userId = 1;
        $name = 'test';
        $email = 'test@gmail.com';
        $password = 'w44wwwww';

        // ユーザーの登録を行うリクエストを送信
        $this->post('/user', ['name' => $name, 'email' => $email, 'password' => $password]);

        // プロフィールのセクションの作成
        $sectionId = 1;
        $data = [
        'userName' => $name,
        'sectionName' => 'test'
        ];
        $this->post('/user/'.$userId.'/sections', $data);

        // ブログの作成
        $blogId = 1;
        $blogData = [
        'userId' => $userId,
        'sectionId' => $sectionId,
        'title' => 'test',
        'text' => Str::random(10000)
        ];
        $this->post('/blog/', $blogData);

        // ブログの取得
        $response = $this->get('/blog/'.$blogId);

        // リクエストの結果の確認
        $now = now()->toDateTimeString();
        $response->assertStatus(200);
        $trueResult = [
        'id' => $blogId,
        'title' => $blogData['title'],
        'text' => $blogData['text'],
        'updated' => substr($now, 0, 10),
        ];
        $response->assertExactJson($trueResult, true);
    }
}
