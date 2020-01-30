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
        return $this->belongsTo('App\OrderAdditionalDetails');
    }

}
