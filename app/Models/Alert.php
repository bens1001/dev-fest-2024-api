<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Alert extends Model
{
    use HasFactory;

    protected $fillable = [
        'machine_id',
        'alert_message',
        'alert_time',
    ];

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    public function users()
    {
        return $this->hasMany(UserAlert::class);
    }
}
