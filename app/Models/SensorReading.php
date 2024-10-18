<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SensorReading extends Model
{
    use HasFactory;

    protected $fillable = [
        'machine_id',
        'sensor_data',
        'reading_time',
    ];

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }
}
