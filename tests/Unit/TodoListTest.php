<?php

namespace Tests\Unit;

use App\Models\TodoList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TodoListTest extends TestCase
{
    use RefreshDatabase;

    private $todoList = [];
    private $todoName = '';

    public function setUp(): void
    {
        parent::setUp();
        $this->todoName = 'todo list item ' . rand(1, 99);
        // TodoList::factory()->count(1)->create() && TodoList::factory()->create() are not same
        // count() creates array of objects while without count give direct individual model object
        $this->todoList = TodoList::factory()->count(2)->create([
            'name' => $this->todoName
        ]);
    }

    /**
     * get all todo list unit test
     */
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
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }
}
