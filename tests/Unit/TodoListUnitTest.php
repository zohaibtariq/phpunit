<?php

namespace Tests\Unit;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class TodoListUnitTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_todo_list_can_have_many_tasks(): void
    {
        $list = $this->createToDoLists()->last();
        $tasks = $this->createTasks(['todo_list_id' => $list->id]);
        $this->assertInstanceOf(Collection::class, $list->tasks);
        $this->assertInstanceOf(Task::class, $list->tasks->first());
    }
}