<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = [
        'code',
        'client_id',
        'client_branch_id',
        'client_position_id'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function applicant()
    {
        return $this->hasOne('App\Applicant');
    }

    public function client()
    {
        return $this->hasOne('App\Client');
    }

    public function branch()
    {
        return $this->hasOne('App\ClientBranch');
    }

    public function position()
    {
        return $this->hasOne('App\ClientPosition');
    }
}
