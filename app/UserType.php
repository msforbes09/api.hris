<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    protected $fillable = ['name'];

    public function users()
    {
        return $this->hasMany('App\Users');
    }

    public function module_actions()
    {
        return $this->belongsToMany('App\ModuleAction', 'accesses', 'user_type_id', 'module_action_id');
    }
}
