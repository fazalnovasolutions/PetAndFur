<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Background extends Model
{
    public function has_category()
    {
        return $this->belongsTo('App\BackgroundCategory', 'category_id');
    }
}
