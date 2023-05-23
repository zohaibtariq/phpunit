<?php

namespace Tests\Feature;

use App\Models\TodoList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodoListFeatureTest extends TestCase
{
    use RefreshDatabase;

    private $todoList = [];
    private $todoName = '';

    public function setUp(): void
    {
        parent::setUp();
        $this->todoName = 'todo list item ' . rand(1, 99);
        $this->todoList = $this->createToDoLists([
            'name' => $this->todoName
        ]);
    }

    public function test_fetch_todo_list(): void
    {
        $response = $this->getJson(route('todo-list.index'));
        $response->assertJson($this->todoList->toArray());
        $this->assertEquals(2, count($response->json()));
        $this->assertEquals($this->todoName, $response->json()[0]['name']);
    }

    public function test_fetch_single_todo_list()
    {
        $todoList = $this->todoList->last();
        $response = $this->getJson(route('todo-list.show', $todoList->id))
            // both are same
            ->assertOk()  // ->assertStatus(200)
            ->json();
        $this->assertEquals($todoList->name, $response['name']);
    }

    public function test_store_new_todo_list()
    {
        $todo = TodoList::factory()->make();  // make will create object dont store in db while create do
        $response = $this->postJson(route('todo-list.store', ['name' => $todo->name]))  // store work actually happened from here
            ->assertCreated()  // ->assertSuccessful() both are same
            ->json();
        $this->assertEquals($response['name'], $todo->name);
        $this->assertDatabaseHas('todo_lists', ['name' => $todo->name]);
    }

    public function test_while_storing_todo_list_name_field_is_required()
    {
        $this->withExceptionHandling();
        $this->postJson(route('todo-list.store'))
            ->assertUnprocessable()  // ->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    public function test_delete_todo_list()
    {
        $todoList = $this->todoList->last();
        $this->deleteJson(route('todo-list.destroy', $todoList->id))
            ->assertNoContent();
        $this->assertDatabaseMissing('todo_lists', ['id' => $todoList->id]);
        $this->assertDatabaseMissing('todo_lists', ['id' => $todoList->id, 'name' => $this->todoName]);
        $this->assertDatabaseMissing('todo_lists', ['id' => $todoList->id, 'name' => $todoList->name]);
    }

    public function test_update_todo_list()
    {
        $newName = 'New Name Of To Do Item';
        $todoList = $this->todoList->last();
        $this->patchJson(route('todo-list.update', $todoList->id), ['name' => $newName])
            ->assertOk();
        $this->assertDatabaseHas('todo_lists', ['id' => $todoList->id, 'name' => $newName]);
    }

    public function test_update_name_is_required_field()
    {
        $todoList = $this->todoList->last();
        $this->withExceptionHandling();
        $this->patchJson(route('todo-list.update', $todoList->id))
            ->assertUnprocessable()  // ->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }
}