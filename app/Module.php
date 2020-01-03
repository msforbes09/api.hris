<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    public $timestamps = false;

    protected $fillable = ['code', 'name'];

    public function moduleActions()
    {
        return $this->hasMany('App\ModuleAction');
    }
}
