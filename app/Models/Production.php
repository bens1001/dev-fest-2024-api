<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Production extends Model
{
    use HasFactory;

    protected $fillable = [
        'machine_id',
        'start_time',
        'end_time',
        'output_quantity',
        'defects_quantity',
    ];

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }
}
