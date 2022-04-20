<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model {

    protected $table      = 'order_details';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'order_id',
        'stock_id',
        'price'
    ];

    public function stock()
    {
        return $this->belongsTo('App\Models\Stock');
    }

    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }

}
