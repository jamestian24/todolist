<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskStoreRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Http\Resources\TaskResource;
use App\Models\member;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use Knuckles\Scribe\Attributes\QueryParam;
use Laravel\Sanctum\PersonalAccessToken;

/**
 * @group Task 任務資料
 * @authenticated
 */
class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    #[QueryParam(name: "sort", type: "string", description: "sort", example: "asc / desc")]
    public function index(Request $request)
    {
        $sort = $request->input("sort");
        if($sort != "asc" and $sort != "desc"){
            return response()->json([
                'Status' => 'error',
                'Message' => 'invalid input of sort',
            ], 422);
        }
        //資源列表快取
        $url = $request->url();
        $queryParams = $request->query();
        ksort($queryParams);
        $queryString = http_build_query($queryParams);
        $fullUrl = "{$url}?{$queryString}";
        if (Cache::has($fullUrl)){
            return Cache::get($fullUrl);
        }
        $query = Task::with('member');
        $limit = $request->limit ?? 10;
        $task = $query->orderBy('id', $sort)->paginate($limit)
        ->appends($request->query());

        $tasks = $query->paginate($limit)->appends($request->query());
        //沒有快取紀錄記住資料，並設定60秒過期，快取名稱使用網址命名。
        return Cache::remember($fullUrl, 60, function () use ($tasks){
            return TaskResource::collection($tasks);
        });
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskStoreRequest $request)
    {
        $val_array = array_merge($request->validated(),['creator_id' => $request->user()->id]);
        Task::create($val_array);

        return response()->json([
            'Status' => 'success',
            'Message' => 'data saved'], 200);

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        if(intval($id) != 0){
            $item = Task::find($id);
            if ($item) {
                return new TaskResource($item);
            } else{
                return response()->json([
                    'Status' => 'error',
                    'Message' => 'id 不存在',
            ], 422);
            }
        } else {
            return response()->json([
                'Status' => 'error',
                'Message' => 'id 必須是整數'], 422);
        }

        // return new TaskResource($task);
    }


    /**
     * Update the specified resource in storage.
     */

    /**
     * @bodyParam task_descr string required The context of the task description.
     * @bodyParam member_id int required The id of the member who is asign to the task. Example: 9
     * @bodyParam creator_id int required The id of the user who creat the task. Example: 9
    */
    public function update(TaskUpdateRequest $request, Task $task)
    {
        $isSuccess = $task->update($request->all());
        if($isSuccess){
            return $this->show($task->id);
        } else {
            return response(['update failed'] , 400);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $item = Task::find($task->id);
        $item->delete();
        return response()->json([
            'Status' => 'success',
            'Message' => '刪除成功'], 200);

    }
}
