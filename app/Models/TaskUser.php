<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class TaskUser extends Pivot
{
    protected $table = 'task_users';

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}