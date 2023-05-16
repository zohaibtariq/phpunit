<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    function index()
    {
        return [
            ['title' => 'i am fake title']
        ];
    }

    function store(Request $request)
    {
        return Task::create($request->all());
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