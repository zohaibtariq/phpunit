<?php

namespace Tests\Unit;

use App\Models\TodoList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskUnitTest extends TestCase
{
    use RefreshDatabase;

    function setUp(): void
    {
        parent::setUp();
        $this->createToDoLists();
        $this->createTasks();
    }

    /**
     * A basic unit test example.
     */
    public function test_task_belong_to_a_todo_list(): void
    {
        $list = $this->createToDoLists()->last();
        $task = $this->createTasks(['todo_list_id' => $list->id])->last();
        $this->assertInstanceOf(TodoList::class, $task->todo_list);
    }
}
