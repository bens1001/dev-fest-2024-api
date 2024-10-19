<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Defect extends Model
{
    use HasFactory;

    protected $fillable = [
        'machine_id',
        'defect_type',
        'defect_time',
    ];

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }
}
