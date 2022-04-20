<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MotorCycle extends Model {

    protected $table      = 'motor_cycles';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'machine',
        'suspension',
        'transmission'
    ];

    public function vehicle()
    {
        return $this->morphMany('App\Models\Vehicle', 'vehicleable');
    }

}
