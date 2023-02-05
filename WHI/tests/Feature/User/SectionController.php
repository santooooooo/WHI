<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Section;
use App\Models\Content;
use App\Models\Blog;

class SectionController extends TestCase
{
    use RefreshDatabase;

    /**
     * プロフィールのセクションの作成機能の確認
     *
     * test
     *
     * @return void
     */
    public function store()
    {
        // ユーザー情報の作成と初期セクションの作成
        $user = User::factory()->create();

        // プロフィールのセクションの作成
        $sectionId = 1;
        $data = [
        'sectionName' => 'test'
        ];
        $response = $this->actingAs($user)->post('/user/'.$user->id.'/sections', $data);

        // リクエストの成功及びレスポンスの値の確認
        $response->assertStatus(200);
        $result = ['id' => $sectionId, 'name' => $data['sectionName']];
        $response->assertExactJson($result, true);

        // セクションのDBの確認
        $this->assertDatabaseHas('sections', ['user_id' => $user->id, 'name' => $data['sectionName']]);
    }

    /**
     * プロフィールのセクションの更新機能の確認
     *
     * test
     *
     * @return void
     */
    public function update()
    {
        // ユーザー情報の作成と初期セクションの作成
        $user = User::factory()->create();
	$section = Section::factory()->for($user)->create();

        // プロフィールのセクションの更新
        $updateData = [
        'userName' => $user->name,
        'oldSectionName' => $section->name,
        'newSectionName' => 'Jamboo'
        ];
        $response = $this->actingAs($user)->put('/sections/'.$user->id, $updateData);

        // リクエストの成功及びレスポンスの値の確認
        $response->assertStatus(200);
        $result = ['id' => $section->id, 'name' => $updateData['newSectionName']];
        $response->assertExactJson($result, true);
    }

    /**
     * プロフィールのセクションの削除機能の確認
     *
     * @test
     *
     * @return void
     */
    public function destroy()
    {
        // ユーザー情報の作成と初期セクションとコンテンツとブログの作成
        $user = User::factory()->create();
	$section = Section::factory()->for($user)->create();
	Content::factory()->state(['user_id' => $user->id, 'section_id' => $section->id])->create();
	Blog::factory()->state(['user_id' => $user->id, 'section_id' => $section->id])->create();

        // プロフィールのセクションの削除
        $deleteData = [
        'userId' => $user->id,
        ];
        $response = $this->actingAs($user)->delete('/sections/'.$section->id, $deleteData);

        // リクエストの成功及びレスポンスの値の確認
        $response->assertStatus(200);
        $result = ['Success'];
        $response->assertExactJson($result, true);

        // セクションのDBの確認
        $this->assertDatabaseMissing('sections', ['user_id' => $user->id, 'name' => $section->name]);
        // 削除するセクション内のコンテンツを削除できたか確認
        $this->assertDatabaseMissing('contents', ['user_id' => $user->id, 'section_id' => $section->id]);
        // 削除するセクション内のブログを削除できたか確認
        $this->assertDatabaseMissing('blogs', ['user_id' => $user->id, 'section_id' => $section->id]);
    }

    /**
     * プロフィールのセクションの取得機能の確認
     *
     * test
     *
     * @return void
     */
    public function index()
    {
        // ユーザー情報の作成と初期セクションの作成
        $user = User::factory()->create();
	$section = Section::factory()->for($user)->create();

        // プロフィールのセクションの取得
        $response = $this->get('/user/'.$user->id.'/sections');

        // リクエストの成功及びレスポンスの値の確認
        $response->assertStatus(200);
        $section = ['id' => $section->id, 'name' => $section->name];
        $result = [$section];
        $response->assertExactJson($result, true);
    }
}
