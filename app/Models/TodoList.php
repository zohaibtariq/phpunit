<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TodoList extends Model
{
    use HasFactory;

    protected $table = 'todo_lists';

    protected $fillable = ['name', 'user_id'];

    function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public static function boot(): void
    {
        parent::boot();
//        self::deleting(function($todo_list){
//            $todo_list->tasks->each()->delete();
//            $todo_list->tasks->each(function($task){
//                $task->delete();
//            });
//        });
    }
}
