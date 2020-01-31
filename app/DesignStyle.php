<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DesignStyle extends Model
{
    protected $fillable=[
        'order_product_id','style','color','category_id'
    ];

}
