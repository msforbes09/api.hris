<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Key extends Model
{
    public $timestamps = false;

    protected $fillable = ['name'];

    public function keywords()
    {
        return $this->hasMany('App\Keyword');
    }
}
