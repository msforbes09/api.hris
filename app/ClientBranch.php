<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientBranch extends Model
{
    use SoftDeletes;

    public $timestamps = false;

    protected $fillable = ['code', 'name'];
}
