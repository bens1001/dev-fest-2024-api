<?php

namespace App\Events;

use App\Http\Resources\SensorReadingResource;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
// use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SensorReadingBroadcasted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $sensorReading;

    public function __construct($sensorReading)
    {
        $this->sensorReading = new SensorReadingResource($sensorReading);
    }

    public function broadcastOn()
    {
        return new Channel('sensor-readings');
    }

    public function broadcastAs()
    {
        return 'sensor.reading';
    }
}
