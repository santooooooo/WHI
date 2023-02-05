<?php
declare(strict_types=1);

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Section;
use App\Models\Content;
use App\Models\Blog;

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
        // ユーザー情報の作成と初期セクションの作成
        $user = User::factory()->create();
        $section = Section::factory()->for($user)->create();

        // ブログの作成
        $blogId = 1;
        $blogData = [
        'userId' => $user->id,
        'sectionId' => $section->id,
        'title' => Str::random(255),
        'text' => Str::random(10000)
        ];
        $response = $this->actingAs($user)->post('/blog/', $blogData);

        // リクエストの結果の確認
        //$appUrl = env('APP_URL');
        //$trueResult = [$appUrl.'/#/blogs/'.$blogId];
        $response->assertExactJson([$blogId], true);
        $response->assertStatus(200);

        // 作成されたブログの確認
        $this->assertDatabaseHas('blogs', ['user_id' => $user->id, 'section_id' => $section->id, 'title' => $blogData['title'], 'text' => $blogData['text']]);

        // 作成されたコンテンツの確認
        $appUrl = env('APP_URL');
        $blogUrl = $appUrl.'/#/blogs/'.$blogId;
        $content = ['section_id' => $section->id, 'type' => 'blog', 'substance' => $blogUrl];

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
        // ユーザー情報の作成と初期セクションの作成
        $user = User::factory()->create();
        $section = Section::factory()->for($user)->create();
        $blog = Blog::factory()->state(['user_id' => $user->id, 'section_id' => $section->id])->create();
        $appUrl = env('APP_URL');
        $blogUrl = $appUrl.'/#/blogs/'.$blog->id;
        Content::factory()->state(['user_id' => $user->id, 'section_id' => $section->id, 'type' => 'blog', 'substance' => $blogUrl])->create();

        // ブログの削除
        $deleteBlogData = [
        'userId' => $user->id,
        'sectionId' => $section->id,
        ];
        $response = $this->actingAs($user)->delete('/blog/'.$blog->id, $deleteBlogData);

        // リクエストの結果の確認
        $response->assertStatus(200);
        $trueResult = ['Success'];
        $response->assertExactJson($trueResult, true);

        // ブログが削除されているか確認
        $this->assertDatabaseMissing('blogs', ['user_id' => $user->id, 'section_id' => $section->id, 'title' => $blog->title, 'text' => $blog->text]);

        // コンテンツが削除されているか確認
        $appUrl = env('APP_URL');
        $blogUrl = $appUrl.'/#/blogs/'.$blog->id;
        $content = ['section_id' => $section->id, 'type' => 'blog', 'substance' => $blogUrl];
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
        // ユーザー情報の作成と初期セクションとブログの作成
        $user = User::factory()->create();
        $section = Section::factory()->for($user)->create();
        $blog = Blog::factory()->state(['user_id' => $user->id, 'section_id' => $section->id])->create();

        // ブログの更新
        $updateBlogData = [
        'userId' => $user->id,
        'title' => Str::random(255),
        'text' => Str::random(10000)
        ];
        $response = $this->actingAs($user)->put('/blog/'.$blog->id, $updateBlogData);

        // リクエストの結果の確認
        $response->assertStatus(200);
        $trueResult = ['Success'];
        $response->assertExactJson($trueResult, true);

        // 更新されたブログの確認
        $this->assertDatabaseHas('blogs', ['user_id' => $user->id, 'section_id' => $section->id, 'title' => $updateBlogData['title'], 'text' => $updateBlogData['text']]);
    }

    /**
     * ブログの取得のテスト
     *
     * test
     *
     * @return void
     */
    public function show()
    {
        // ユーザー情報の作成と初期セクションとブログの作成
        $user = User::factory()->create();
        $section = Section::factory()->for($user)->create();
        $blog = Blog::factory()->state(['user_id' => $user->id, 'section_id' => $section->id])->create();

        // ブログの取得
        $response = $this->get('/blog/'.$blog->id);

        // リクエストの結果の確認
        $now = now()->toDateTimeString();
        $response->assertStatus(200);
        $trueResult = [
        'id' => $blog->id,
        'user_id' => $user->id,
        'title' => $blog->title,
        'text' => $blog->text,
        'updated' => substr($now, 0, 10),
        ];
        $response->assertExactJson($trueResult, true);
    }
}
