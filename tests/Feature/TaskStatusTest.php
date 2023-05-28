<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskStatusTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_task_status_is_updating(): void
    {
        $this->createAuthUser();
        $task = $this->createTasks()->last();
        $this->patchJson(route('task.update', $task->id), ['status' => Task::STARTED]);
        $this->assertDatabaseHas('tasks', ['status' => Task::STARTED]);
    }
}
