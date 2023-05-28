<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
// use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskFeatureTest extends TestCase
{
    use RefreshDatabase;

    private $tasks;
    private $todoList = [];

    protected $currentAuthUser;

    public function setUp(): void
    {
        parent::setUp();
        $this->currentAuthUser = $this->createAuthUser();
        $this->todoList = $this->createToDoLists();
        // $this->tasks = $this->createTasks(['todo_list_id' => $this->todoList->last()->id]); // this will created related task
        $this->tasks = $this->createTasks();  // this will create unrelated task
    }

    public function test_fetch_all_tasks_of_a_todo_list(): void
    {
        // task created inside constructor is one unrelated task so it must not be count to verify the test is showing counting only related task from todo
        $todoList = $this->todoList->last();
        $this->createTasks(['todo_list_id' => $todoList->id]);
        $response = $this->getJson(route('todo-list.task.index', $todoList->id))->assertOk()->json();
        $this->assertEquals(count($this->tasks), count($response));
    }

    public function test_store_task_for_todo()
    {
        $todoList = $this->todoList->last();
        $task = Task::factory()->make();
        $label = $this->createLabel(['user_id' => $this->currentAuthUser->id]);
        $taskData = ['title' => $task->title, 'description' => 'long description', 'todo_list_id' => $todoList->id, 'label_id' => $label->id];

        $response = $this->postJson(route('todo-list.task.store', $todoList->id), $taskData)
            ->assertCreated();

        $this->assertDatabaseHas('tasks', $taskData);
        $this->assertDatabaseHas('tasks', ['id' => $response->json()['id'], 'status' => Task::NOT_STARTED]);
    }

    public function test_store_task_without_label()
    {
        $todoList = $this->todoList->last();
        $task = Task::factory()->make();
        $taskData = ['title' => $task->title, 'description' => 'long description', 'todo_list_id' => $todoList->id];

        $response = $this->postJson(route('todo-list.task.store', $todoList->id), $taskData)
            ->assertCreated();

        $this->assertDatabaseHas('tasks', $taskData);
        $this->assertDatabaseHas('tasks', ['id' => $response->json()['id'], 'status' => Task::NOT_STARTED]);
    }

    public function test_task_delete()
    {
        $task = $this->tasks->first();
        $this->deleteJson(route('task.destroy', $task->id))
            ->assertNoContent();
        $this->assertDatabaseMissing('tasks', ['title' => $task->title]);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_update_a_task_of_todo_list()
    {
        $task = $this->tasks->first();
        $taskData = ['title' => 'updated title ' . rand(1, 99)];
        $this->patchJson(route('task.update', $task->id), $taskData)
            ->assertOk();
        $this->assertDatabaseHas('tasks', $taskData);
    }
}
