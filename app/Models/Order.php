<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model {

    protected $table      = 'orders';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'number',
        'total'
    ];

    public function orderDetail()
    {
        return $this->hasMany('App\Models\OrderDetail');
    }

}
