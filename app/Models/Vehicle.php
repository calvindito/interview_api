<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model {

    protected $table      = 'vehicles';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'vehicleable_type',
        'vehicleable_id',
        'release',
        'price',
        'color'
    ];

    public function vehicleable()
    {
        return $this->morphTo();
    }

    public function stock()
    {
        return $this->hasMany('App\Models\Stock');
    }

}
