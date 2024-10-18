<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EnergyUsage extends Model
{
    use HasFactory;

    protected $fillable = [
        'machine_id',
        'energy_consumed',
        'start_shift_time',
        'end_shift_time',
    ];

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }
}
