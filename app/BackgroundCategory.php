<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BackgroundCategory extends Model
{

    public function has_backgrounds()
    {
        return $this->hasMany('App\Background','category_id');
    }
}
