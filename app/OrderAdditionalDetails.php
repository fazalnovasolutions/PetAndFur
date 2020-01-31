<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderAdditionalDetails extends Model
{
    protected $fillable = [
        'status','order_id','shop_id','designer_id','status_id','created_at','updated_at'
    ];
    public function has_additional_details()
    {
        return $this->belongsTo('App\Order', 'order_id');
    }
}
