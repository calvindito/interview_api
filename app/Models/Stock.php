<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model {

    protected $table      = 'stocks';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'vehicle_id',
        'frame'
    ];

    public function vehicle()
    {
        return $this->belongsTo('App\Models\Vehicle');
    }

    public function orderDetail()
    {
        return $this->hasOne('App\Models\OrderDetail');
    }

}
