<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientPosition extends Model
{
    public $timestamps = false;

    protected $fillable = ['code', 'name'];
}
