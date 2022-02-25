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
}
