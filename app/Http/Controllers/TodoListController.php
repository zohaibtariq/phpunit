<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodoListRequest;
use App\Models\TodoList;
use Symfony\Component\HttpFoundation\Response;

class TodoListController extends Controller
{
    function index()
    {
        return response(TodoList::all());
    }

    function show(TodoList $todoList)
    {
        // TodoList::findOrFail($id); // no need of this line if we use route modle binding means type hint class in param
        return response($todoList);
    }

    function store(TodoListRequest $request)
    {
        return response(TodoList::create($request->all()), Response::HTTP_CREATED);
    }

    function destroy(TodoList $todoList)
    {
        $todoList->delete();
        return response('', Response::HTTP_NO_CONTENT);
    }

    function update(TodoListRequest $request, TodoList $todoList)
    {
        return $todoList->update($request->all());  // it will auto send 200 status
    }
}
