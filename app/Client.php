<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    public $timestamps = false;

    protected $fillable = ['company_id', 'code', 'name'];

    public function branches()
    {
        return $this->hasMany('App\ClientBranch');
    }

    public function positions()
    {
        return $this->hasMany('App\ClientPosition', );
    }
}
