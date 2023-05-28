<?php

namespace Tests;

use App\Models\Label;
use App\Models\Task;
use App\Models\TodoList;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use WithFaker;

    function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        $this->setUpFaker();
    }

    function createToDoLists($args = [])
    {
        // TodoList::factory()->count(1)->create() && TodoList::factory()->create() are not same
        // count() creates array of objects while without count give direct individual model object
        return TodoList::factory()->count(2)->create($args);
    }

    function createTasks($args = [])
    {
        return Task::factory()->count(1)->create($args);
    }

    function createUser($args = []){
        return User::factory()->create($args);
    }

    function createAuthUser(){
        $user = $this->createUser();
        Sanctum::actingAs($user);
        return $user;
    }

    function createLabel($args = []){
        return Label::factory()->create($args);
    }

}
