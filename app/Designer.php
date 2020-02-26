<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Designer extends Model
{
   public function has_orders()
   {
       return $this->hasMany('App\Order','designer_id');
   }
   public function has_reviews(){
       return $this->hasMany('App\ReviewRating','designer_id');
   }
   public function is_user(){
       return $this->hasOne('App\User','designer_id');
   }
}
