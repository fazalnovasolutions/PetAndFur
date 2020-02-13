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
    public function has_request_fixes()
    {
        return $this->hasMany('App\RequestFix','order_product_id');
    }
    public function has_background()
    {
        return $this->belongsTo('App\Background','background_id');
    }

    public function has_new_photos()
    {
        return $this->hasMany('App\NewPhoto','order_product_id');
    }

    public function has_many_designs(){
        return $this->hasMany('App\ProductDesign','order_product_id');
    }
}
