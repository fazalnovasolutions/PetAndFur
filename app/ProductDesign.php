<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductDesign extends Model
{
    public function has_background()
    {
        return $this->belongsTo('App\Background','background_id');
    }

}
