<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmsStatus extends Model
{
    protected $fillable = ['applicant_id', 'sms_id', 'mt_id', 'status'];

    public function sms()
    {
        return $this->belongsTo('App\Sms');
    }
}
