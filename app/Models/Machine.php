<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Machine extends Model
{
    use HasFactory;

    protected $fillable = [
        'machine_type',
        'machine_name',
        'status',
        'last_maintenance',
        'first_usage',
    ];

    public function sensorReadings()
    {
        return $this->hasMany(SensorReading::class);
    }

    public function energyUsages()
    {
        return $this->hasMany(EnergyUsage::class);
    }

    public function productions()
    {
        return $this->hasMany(Production::class);
    }

    public function alerts()
    {
        return $this->hasMany(Alert::class);
    }
}
