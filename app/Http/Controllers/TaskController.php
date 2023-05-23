<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TodoList;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    function index(Request $request, TodoList $todo_list)
    {
        return $todo_list->tasks;
        // return Task::where('todo_list_id', $todo_list->id)->get();
    }

    function store(Request $request, TodoList $todo_list)
    {
        return $todo_list->tasks()->create($request->all());
        // $request->request->add(['todo_list_id' => $todo_list->id]);
        // return Task::create($request->all());
    }

    function destroy(Task $task)
    {
        $statusCode = Response::HTTP_OK;
        if ($task->delete())
            $statusCode = Response::HTTP_NO_CONTENT;
        return response([], $statusCode);
    }

    function update(Request $request, Task $task)
    {
        return $task->update($request->all());
    }
}