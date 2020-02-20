<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sms extends Model
{
    protected $fillable = ['user_id', 'title', 'message', 'schedule'];

    public function recipients()
    {
        return $this->hasMany('App\SmsRecipient');
    }

    public function parsedSms(Applicant $applicant) {
        $parsedSms = $this->message;

        foreach ($applicant->getAttributes() as $key => $value)
        {
            $parsedSms = str_replace('~'.$key.'~', $value, $parsedSms);
        }

        return $parsedSms;
    }
}
