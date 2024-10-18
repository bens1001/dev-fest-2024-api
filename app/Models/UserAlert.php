<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserAlert extends Pivot
{
    protected $table = 'user_alerts';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function alert()
    {
        return $this->belongsTo(Alert::class);
    }
}
