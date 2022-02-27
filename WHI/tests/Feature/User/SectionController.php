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
     * @test
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
        $data = [
        'userName' => $name,
        'sectionName' => 'test'
        ];
        $response = $this->post('/user/'.$id.'/sections', $data);

        // リクエストの成功及びレスポンスの値の確認
        $response->assertStatus(200);
        $result = [$data['sectionName']];
        $response->assertExactJson($result, true);

        // セクションのDBの確認
        $this->assertDatabaseHas('sections', ['user_id' => $id, 'name' => $data['sectionName']]);
    }

    /**
     * プロフィールのセクションの更新機能の確認
     *
     * @test
     * @return void
     */
    public function update()
    {
        // このファイル内の全てのテストを同時に行う際のデータベースの初期化
        DB::table('users')->truncate();
        DB::table('sections')->truncate();

        // ユーザー情報
        $id = 1;
        $name = 'test';
        $email = 'test@gmail.com';
        $password = 'w44wwwww';

        // ユーザーの登録を行うリクエストを送信
        $this->post('/user', ['name' => $name, 'email' => $email, 'password' => $password]);

        // プロフィールのセクションの作成
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
        $result = [$updateData['newSectionName']];
        $response->assertExactJson($result, true);
    }

    /**
     * プロフィールのセクションの削除機能の確認
     *
     * @test
     * @return void
     */
    public function destroy()
    {
        // このファイル内の全てのテストを同時に行う際のデータベースの初期化
        DB::table('users')->truncate();
        DB::table('sections')->truncate();

        // ユーザー情報
        $id = 1;
        $name = 'test';
        $email = 'test@gmail.com';
        $password = 'w44wwwww';

        // ユーザーの登録を行うリクエストを送信
        $this->post('/user', ['name' => $name, 'email' => $email, 'password' => $password]);

        // プロフィールのセクションの作成
        $storeData = [
        'userName' => $name,
        'sectionName' => 'test'
        ];
        $this->post('/user/'.$id.'/sections', $storeData);

        $deleteData = [
        'userName' => $name,
        'sectionName' => $storeData['sectionName'],
        ];
        $response = $this->delete('/sections/'.$id, $deleteData);

        $response->assertStatus(200);
        // セクションのDBの確認
        $this->assertDatabaseMissing('sections', ['user_id' => $id, 'name' => $storeData['sectionName']]);
    }

    /**
     * プロフィールのセクションの取得機能の確認
     *
     * @test
     * @return void
     */
    public function index()
    {
        // このファイル内の全てのテストを同時に行う際のデータベースの初期化
        DB::table('users')->truncate();
        DB::table('sections')->truncate();

        // ユーザー情報
        $id = 1;
        $name = 'test';
        $email = 'test@gmail.com';
        $password = 'w44wwwww';

        // ユーザーの登録を行うリクエストを送信
        $this->post('/user', ['name' => $name, 'email' => $email, 'password' => $password]);

        // プロフィールのセクションの作成
        $storeData = [
        'userName' => $name,
        'sectionName' => 'test'
        ];
        $this->post('/user/'.$id.'/sections', $storeData);

        // プロフィールのセクションの取得
        $response = $this->get('/user/'.$id.'/sections');

        // リクエストの成功及びレスポンスの値の確認
        $response->assertStatus(200);
        $result = [$storeData['sectionName']];
        $response->assertExactJson($result, true);
    }
}
