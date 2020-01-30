<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderAdditionalDetails extends Model
{
    public function has_additional_details()
    {
        return $this->belongsTo('App\Order', 'order_id');
    }
}
