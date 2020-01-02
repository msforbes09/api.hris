<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModuleAction extends Model
{
    public $timestamps = false;

    protected $fillable = ['code', 'name'];

    public function module()
    {
        return $this->belongsTo('App\Module');
    }

    public function user_types()
    {
        return $this->belongsToMany('App\UserType', 'accesses', 'user_type_id', 'module_action_id');
    }

}
