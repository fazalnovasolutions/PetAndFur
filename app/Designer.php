<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Designer extends Model
{
   public function has_orders()
   {
       return $this->hasMany('App\Order','designer_id');
   }
}
