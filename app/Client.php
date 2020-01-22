<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes;

    public $timestamps = false;

    protected $fillable = ['company_id', 'code', 'name'];

    public function company()
    {
        return $this->belongsTo('App\Company');
    }

    public function branches()
    {
        return $this->hasMany('App\ClientBranch');
    }

    public function positions()
    {
        return $this->hasMany('App\ClientPosition');
    }
}
