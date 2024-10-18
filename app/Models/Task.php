<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_description',
        'due_date',
        'status',
    ];

    public function taskUsers()
    {
        return $this->belongsToMany(User::class, 'task_users');
    }
}
