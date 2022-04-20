<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model {

    protected $table      = 'cars';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'machine',
        'passenger_capacity',
        'type'
    ];

    public function vehicle()
    {
        return $this->morphMany('App\Models\Vehicle', 'vehicleable');
    }

}
