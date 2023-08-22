<?php

namespace Tests\Feature;

use App\Models\member;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void{
        parent::setUp();
        User::factory()->create();

    }
    /**
     * 測試查看 Task 列表的Json結構
     *
     * @return void
     */
    public function testViewAllTask(): void
    {
        $this->actingAs(User::find(1));
        Task::factory(10)->create();
        member::factory(12)->create();
        User::factory(6)->create();

        $response = $this->json('GET', 'api/task/?sort=asc');
        $this->withExceptionHandling();

        $resultStructure = [
            "data" => [
                '*' => [
                    "id", "task_descr", "member_id", 'member_name', 'member_group', 'creator_id', 'created_at', 'updated_at'
                ]
            ],
            "links" => [
                "first", "last", "prev", "next"
            ],
            "meta" => [
                "current_page", "from", "last_page", "path", "per_page", "to", "total"
            ]
        ];

        $response->assertStatus(200)->assertJsonStructure($resultStructure);
    }
    public function testCanNotCreatTask(){
        //誰?沒有模擬會員權限的程式

        //建立一個member資料
        $member = member::factory()->create();

        //做什麼事?請求時並傳入資料
        $response = $this->json(
            'POST',
            'api/tasks',
            [
                'task_descr' => 'test',
                'member_id' => '1'
            ]
        );

        //結果? 檢查返回資料，沒有token，狀態碼回應401
        $response->assertStatus(401)->assertJson(
            [
                'Status' => 'success',
                'Message' => 'data saved'
            ]
            );
    }
}
