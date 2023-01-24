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

class ContentController extends TestCase
{
    use RefreshDatabase;
    /**
     * セクションのコンテンツの作成のテスト
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

        // セクションのコンテンツの作成
        $contentId = 1;
        $contentData = [
        'userId' => $user->id,
        'sectionId' => $section->id,
        'type' => 'text',
        'substance' => 'testtest',
        ];
        $response = $this->actingAs($user)->post('/user/'.$user->id.'/contents', $contentData);

        // リクエストの成功及びレスポンスの値の確認
        $response->assertStatus(200);
        $trueData = ['id' => $contentId, 'section_id' => $section->id, 'type' => $contentData['type'], 'substance' => $contentData['substance']];
        $response->assertExactJson($trueData, true);

        // コンテンツのDBの確認
        $this->assertDatabaseHas('contents', $trueData);
    }

    /**
     * セクションのコンテンツの取得のテスト
     *
     * test
     *
     * @return void
     */
    public function index()
    {
        // ユーザー情報の作成と初期セクションとコンテンツの作成
        $user = User::factory()->create();
        $section = Section::factory()->for($user)->create();
        $content = Content::factory()->state(['user_id' => $user->id, 'section_id' => $section->id])->create();

        // プロフィールのコンテンツの取得
        $response = $this->get('/user/'.$user->id.'/contents');

        // リクエストの成功及びレスポンスの値の確認
        $response->assertStatus(200);
        $result = ['id' => $content->id, 'section_id' => $section->id, 'type' => $content->type, 'substance' => $content->substance];
        $trueData = [$result];
        $response->assertExactJson($trueData, true);
    }

    /**
     * セクションのコンテンツの削除のテスト
     *
     * test
     *
     * @return void
     */
    public function destroy()
    {
        // ユーザー情報の作成と初期セクションとコンテンツの作成
        $user = User::factory()->create();
        $section = Section::factory()->for($user)->create();
        $content = Content::factory()->state(['user_id' => $user->id, 'section_id' => $section->id])->create();

        // セクションのコンテンツの削除
        $deleteData = [
        'userId' => $user->id,
        'sectionId' => $section->id,
        ];
        $response = $this->actingAs($user)->delete('/contents/'.$content->id, $deleteData);

        // リクエストの成功及びレスポンスの値の確認
        $response->assertStatus(200);

        // コンテンツのDBの確認
        $missingData = ['id' => $content->id,'user_id' => $user->id, 'section_id' => $section->id, 'type' => $content->type, 'substance' => $content->substance];
        $this->assertDatabaseMissing('contents', $missingData);
    }

    /**
     * セクションのコンテンツの更新のテスト
     *
     * @test
     * @return void
     */
    public function update()
    {
        // ユーザー情報の作成と初期セクションとコンテンツの作成
        $user = User::factory()->create();
        $section = Section::factory()->for($user)->create();
        $content = Content::factory()->state(['user_id' => $user->id, 'section_id' => $section->id])->create();

        // セクションのコンテンツの更新
        $updateData = [
        'userId' => $user->id,
        'sectionId' => $section->id,
        'substance' => Str::random(10000),
        ];
        $response = $this->actingAs($user)->put('/contents/'.$content->id, $updateData);

        // リクエストの成功及びレスポンスの値の確認
        $response->assertStatus(200);
        $content = ['id' => $content->id, 'section_id' => $section->id, 'type' => $content->type, 'substance' => $updateData['substance']];
        $response->assertExactJson($content, true);

        // コンテンツのDBの確認
        $this->assertDatabaseHas('contents', $content);
    }
}
