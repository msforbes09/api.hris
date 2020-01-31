<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sms extends Model
{
    protected $fillable = ['user_id', 'title', 'message', 'schedule'];

    public function statuses()
    {
        return $this->hasMany('App\SmsStatus');
    }
}
