<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function has_products()
    {
        return $this->hasMany('App\OrderProduct');
    }

    public function has_additional_details()
    {
        return $this->hasOne('App\OrderAdditionalDetails','order_id');
    }
    public function has_design_details()
    {
        return $this->hasOne('App\OrderProductAdditionalDetails','order_id');
    }

    public function has_designer()
    {
        return $this->belongsTo('App\Designer','designer_id');
    }
}
