<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    public function has_order()
    {
        return $this->belongsTo('App\Order', 'order_id');
    }

    public function has_design()
    {
        return $this->hasOne('App\OrderProductAdditionalDetails','order_product_id');
    }

    public function has_changed_style()
    {
        return $this->hasOne('App\DesignStyle','order_product_id');
    }
}
