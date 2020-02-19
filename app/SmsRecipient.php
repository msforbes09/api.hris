<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmsRecipient extends Model
{
    protected $fillable = ['applicant_id', 'sms_id', 'status'];

    public function sms()
    {
        return $this->belongsTo('App\Sms');
    }

    public function applicant()
    {
        return $this->belongsTo('App\Applicant');
    }

    public function getContactAttribute()
    {
        return $this->applicant->contact_no;
    }

    public function getFullNameAttribute()
    {
        return $this->applicant->full_name;
    }
}
