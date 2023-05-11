<?php

namespace App\Http\Controllers;

use App\Models\TodoList;
use Illuminate\Http\Request;
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

    function store(Request $request)
    {
        $request->validate(['name' => ['required']]);
        return response(TodoList::create($request->all()), Response::HTTP_CREATED);
    }

    function destroy(TodoList $todoList)
    {
        $todoList->delete();
        // dd(TodoList::find($todoList)->toArray());
        return response('', Response::HTTP_NO_CONTENT);
    }

    function update(Request $request, TodoList $todoList)
    {
        $request->validate(['name' => ['required']]);
        return $todoList->update($request->all());
        // dd(TodoList::find($todoList)->toArray());
        // return response('', Response::HTTP_NO_CONTENT);
    }
}
