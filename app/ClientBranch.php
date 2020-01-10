<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientBranch extends Model
{
    public $timestamps = false;

    protected $fillable = ['code', 'name'];
}
