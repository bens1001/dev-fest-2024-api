<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_description',
        'user_id',
        'due_date',
        'status',
    ];

    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
