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

    public function test_on_deleting_todo_list_all_related_tasks_must_delete(){

        $lists = $this->createToDoLists();

        $list1 = $lists->first();
        $list1Tasks = $this->createTasks(['todo_list_id' => $list1->id])->last();

        $list2 = $lists->last();
        $list2Tasks = $this->createTasks(['todo_list_id' => $list2->id])->last();

        $list1->delete();

        $this->assertDatabaseMissing('todo_lists', ['id' => $list1->id]);
        $this->assertDatabaseMissing('tasks', ['id' => $list1Tasks->id]);

        $this->assertDatabaseHas('todo_lists', ['id' => $list2->id]);
        $this->assertDatabaseHas('tasks', ['id' => $list2Tasks->id]);

    }
}
