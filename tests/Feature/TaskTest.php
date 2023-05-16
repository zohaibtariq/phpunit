<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
// use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    private $tasks;
    private $todoList = [];

    public function setUp(): void
    {
        parent::setUp();
        $this->tasks = $this->createTasks();
        $this->todoList = $this->createTasks();
    }

    /**
     * A basic feature test example.
     */
    public function test_fetch_all_tasks_of_a_todo_list(): void
    {
        $todoList = $this->todoList->last();
        $response = $this->getJson(route('todo-list.task.index', $todoList->id))->assertOk()->json();
        $this->assertEquals(count($this->tasks), count($response));
    }

    public function test_store_task_for_todo()
    {
        $todoList = $this->todoList->last();
        $task = Task::factory()->make();
        $taskData = ['title' => $task->title];
        $this->postJson(route('todo-list.task.store', $todoList->id), $taskData)
            ->assertCreated();
        $this->assertDatabaseHas('tasks', $taskData);
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