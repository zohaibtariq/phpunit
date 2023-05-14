<?php

namespace Tests;

use App\Models\TodoList;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    function createToDoLists($args = [])
    {
        // TodoList::factory()->count(1)->create() && TodoList::factory()->create() are not same
        // count() creates array of objects while without count give direct individual model object
        return TodoList::factory()->count(2)->create($args);
    }
}
