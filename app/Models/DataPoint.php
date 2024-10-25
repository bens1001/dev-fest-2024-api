<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DataPoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'timestamp',
        'kpi_name',
        'kpi_value',
        'status',
    ];

    public function alert()
    {
        return $this->hasOne(Alert::class);
    }
}
