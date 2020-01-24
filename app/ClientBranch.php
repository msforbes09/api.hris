<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientBranch extends Model implements Auditable
{
    use SoftDeletes, \OwenIt\Auditing\Auditable;

    public $timestamps = false;

    protected $fillable = ['code', 'name'];
}
