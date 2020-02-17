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
        return $this->hasMany('App\OrderProductAdditionalDetails','order_id');
    }

    public function has_designer()
    {
        return $this->belongsTo('App\Designer','designer_id');
    }
    public function has_new_photo()
    {
        return $this->hasMany('App\NewPhoto','order_id');
    }

    public function has_request_fixes()
    {
        return $this->hasMany('App\RequestFix','order_id');
    }

    public function has_customer_new_messages(){
        return $this->hasMany('App\ChatNotification','order_id');
    }

}
