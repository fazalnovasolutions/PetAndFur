<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReviewRating extends Model
{
    public function has_order(){
       return $this->belongsTo('App\Order','order_id');
    }
}
