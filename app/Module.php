<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    public $timestamps = false;

    public function module_actions()
    {
        return $this->hasMany('App\ModuleAction');
    }
}
