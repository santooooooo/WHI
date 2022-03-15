<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;

class ContentController extends TestCase
{
    use RefreshDatabase;
    /**
     * プロフィールのコンテンツの作成のテスト
     *
     * test
     *
     * @return void
     */
    public function store()
    {
        // ユーザー情報
        $id = 1;
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
        $this->post('/user/'.$id.'/sections', $data);

        // プロフィールのコンテンツの作成
        $contentId = 1;
        $contentData = [
        'userId' => $id,
        'sectionId' => $sectionId,
        'type' => 'text',
        'substance' => 'testtest',
        ];
        $response = $this->post('/user/'.$id.'/contents', $contentData);

        // リクエストの成功及びレスポンスの値の確認
        $response->assertStatus(200);
        $trueData = ['id' => $contentId, 'section_id' => $sectionId, 'type' => $contentData['type'], 'substance' => $contentData['substance']];
        $response->assertExactJson($trueData, true);

        // コンテンツのDBの確認
        $this->assertDatabaseHas('contents', $trueData);
    }

    /**
     * プロフィールのコンテンツの取得のテスト
     *
     * @test
     * @return void
     */
    public function index()
    {
        // ユーザー情報
        $id = 1;
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
        $this->post('/user/'.$id.'/sections', $data);

        // プロフィールのコンテンツの作成
        $contentId = 1;
        $contentData = [
        'userId' => $id,
        'sectionId' => $sectionId,
        'type' => 'text',
        'substance' => 'testtest',
        ];
        $this->post('/user/'.$id.'/contents', $contentData);

        $this->post('/user/'.$id.'/contents', $contentData);

	$this->assertDatabaseCount('contents', 2);

        // プロフィールのコンテンツの取得
        $response = $this->get('/user/'.$id.'/contents');

        // リクエストの成功及びレスポンスの値の確認
        $response->assertStatus(200);
        $content = ['id' => $contentId, 'section_id' => $sectionId, 'type' => $contentData['type'], 'substance' => $contentData['substance']];
        $content2 = ['id' => $contentId+1, 'section_id' => $sectionId, 'type' => $contentData['type'], 'substance' => $contentData['substance']];
        $trueData = [$content, $content2];
        $response->assertExactJson($trueData, true);
    }

    /**
     * プロフィールのコンテンツの削除のテスト
     *
     * test
     * @return void
     */
    public function destroy()
    {
        // ユーザー情報
        $id = 1;
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
        $this->post('/user/'.$id.'/sections', $data);

        // プロフィールのコンテンツの作成
        $contentId = 1;
        $contentData = [
        'userId' => $id,
        'sectionId' => $sectionId,
        'type' => 'text',
        'substance' => 'testtest',
        ];
        $this->post('/user/'.$id.'/contents', $contentData);

        // プロフィールのコンテンツの削除
        $deleteData = [
        'userId' => $id,
        'sectionId' => $sectionId,
        ];
        $response = $this->delete('/contents/'.$contentId, $deleteData);

        // リクエストの成功及びレスポンスの値の確認
        $response->assertStatus(200);

        // コンテンツのDBの確認
        $missingData = ['id' => $contentId,'user_id' => $id, 'section_id' => $sectionId, 'type' => $contentData['type'], 'substance' => $contentData['substance']];
        $this->assertDatabaseMissing('contents', $missingData);
    }

    /**
     * プロフィールのコンテンツの更新のテスト
     *
     * test
     * @return void
     */
    public function update()
    {
        // ユーザー情報
        $id = 1;
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
        $this->post('/user/'.$id.'/sections', $data);

        // プロフィールのコンテンツの作成
        $contentId = 1;
        $contentData = [
        'userId' => $id,
        'sectionId' => $sectionId,
        'type' => 'text',
        'substance' => Str::random(10000),
        ];
        $this->post('/user/'.$id.'/contents', $contentData);

        // プロフィールのコンテンツの更新
        $updateData = [
        'userId' => $id,
        'sectionId' => $sectionId,
        'substance' => Str::random(10000),
        ];
        $response = $this->put('/contents/'.$contentId, $updateData);

        // リクエストの成功及びレスポンスの値の確認
        $response->assertStatus(200);
        $content = ['id' => $contentId, 'section_id' => $updateData['sectionId'], 'type' => $contentData['type'], 'substance' => $updateData['substance']];
        $response->assertExactJson($content, true);

        // コンテンツのDBの確認
        $this->assertDatabaseHas('contents', $content);
    }
}
