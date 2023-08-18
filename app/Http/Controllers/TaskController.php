<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;


class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $limit = $request->limit ?? 10;

        $query = Task::with('member');
        $sort = $request->input("sort");


        $task = $query->orderBy('id', $sort)->paginate($limit)
        ->appends($request->query());

        $fullUrl = $request->fullUrl();
        return Cache::remember($fullUrl, 60, function () use ($task){
            return TaskResource::collection($task);
        });
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'task_descr' => 'required|string|max:255',
            'member_id' => 'required|int|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $task = new Task;
        $task->task_descr = $request->task_descr;
        $task->member_id = $request->member_id;
        $task->save();

        return response()->json(['data saved'], 200);

    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
        return new TaskResource($task);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        //
        $isSuccess = $task->update($request->all());
        if($isSuccess){
            return $this->show($task->refresh());
        } else {
            return response(['update failed'] , 400);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        //
        $task->delete();
        return response(['update failed'] , 204);
    }
}
