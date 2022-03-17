<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

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
        $response = $this->post('/user/'.$id.'/sections', $data);

        // リクエストの成功及びレスポンスの値の確認
        $response->assertStatus(200);
        $result = ['id' => $sectionId, 'name' => $data['sectionName']];
        $response->assertExactJson($result, true);

        // セクションのDBの確認
        $this->assertDatabaseHas('sections', ['user_id' => $id, 'name' => $data['sectionName']]);
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
        // ユーザー情報
        $id = 1;
        $name = 'test';
        $email = 'test@gmail.com';
        $password = 'w44wwwww';

        // ユーザーの登録を行うリクエストを送信
        $this->post('/user', ['name' => $name, 'email' => $email, 'password' => $password]);

        // プロフィールのセクションの作成
        $sectionId = 1;
        $storeData = [
        'userName' => $name,
        'sectionName' => 'test'
        ];
        $this->post('/user/'.$id.'/sections', $storeData);

        // プロフィールのセクションの更新
        $updateData = [
        'userName' => $name,
        'oldSectionName' => $storeData['sectionName'],
        'newSectionName' => 'Jamboo'
        ];
        $response = $this->put('/sections/'.$id, $updateData);

        // リクエストの成功及びレスポンスの値の確認
        $response->assertStatus(200);
        $result = ['id' => $sectionId, 'name' => $updateData['newSectionName']];
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
        // ユーザー情報
        $userId = 1;
        $name = 'test';
        $email = 'test@gmail.com';
        $password = 'w44wwwww';

        // ユーザーの登録を行うリクエストを送信
        $this->post('/user', ['name' => $name, 'email' => $email, 'password' => $password]);

        // プロフィールのセクションの作成
        $sectionId = 1;
        $storeData = [
        'userName' => $name,
        'sectionName' => 'test'
        ];
        $this->post('/user/'.$userId.'/sections', $storeData);

        // プロフィールのコンテンツの作成
        $contentData = [
        'userId' => $userId,
        'sectionId' => $sectionId,
        'type' => 'text',
        'substance' => 'testtest',
        ];
        $this->post('/user/'.$userId.'/contents', $contentData);

        // プロフィールのセクションの削除
        $deleteData = [
        'userId' => $userId,
        ];
        $response = $this->delete('/sections/'.$sectionId, $deleteData);

        // リクエストの成功及びレスポンスの値の確認
        $response->assertStatus(200);
        $result = ['Success'];
        $response->assertExactJson($result, true);

        // セクションのDBの確認
        $this->assertDatabaseMissing('sections', ['user_id' => $userId, 'name' => $storeData['sectionName']]);

        // 削除するセクション内のコンテンツを削除できたか確認
        $deleteContent = ['section_id' => $sectionId, 'type' => $contentData['type'], 'substance' => $contentData['substance']];
        $this->assertDatabaseMissing('contents', $deleteContent);
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
        // ユーザー情報
        $id = 1;
        $name = 'test';
        $email = 'test@gmail.com';
        $password = 'w44wwwww';

        // ユーザーの登録を行うリクエストを送信
        $this->post('/user', ['name' => $name, 'email' => $email, 'password' => $password]);

        // プロフィールのセクションの作成
        $sectionId = 1;
        $storeData = [
        'userName' => $name,
        'sectionName' => 'test'
        ];
        $this->post('/user/'.$id.'/sections', $storeData);

        // プロフィールのセクションの取得
        $response = $this->get('/user/'.$id.'/sections');

        // リクエストの成功及びレスポンスの値の確認
        $response->assertStatus(200);
        $section = ['id' => $sectionId, 'name' => $storeData['sectionName']];
        $result = [$section];
        $response->assertExactJson($result, true);
    }
}
