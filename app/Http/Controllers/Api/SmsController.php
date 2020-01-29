<?php

namespace App\Http\Controllers\Api;

use App\Applicant;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SmsController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'message' => 'required'
        ]);

        $http = new Client();
        $applicants = Applicant::find(explode(',', $request->id));

        $contacts = $applicants->map(function($applicant) {
            if(preg_match('/^\d{11}$/', $applicant->contact_no))
            {
                return '63' . substr($applicant->contact_no, 1);
            }

            abort(403, 'Applicant ['. $applicant->fullname() . '] has an invalid contact number.');
        });

        $result = $http->get(config('app.sms_api') . 'api.aspx', [
            'query' => [
                'apiusername' => config('app.sms_username'),
                'apipassword' => config('app.sms_password'),
                'languagetype' => 1,
                'senderid' => config('app.sms_sender_id'),
                'mobileno' => implode(',', $contacts->toArray()),
                'message' => $request->message
            ]
        ]);

        if($result->getbody() == '-100')
        {
            abort(403, 'Sms API invalid username or password.');
        }
        elseif($result->getBody() == '-200')
        {
            abort(403, 'Sms Api sender id is invalid.');
        }
        elseif($result->getBody() == '-300')
        {
            abort(403, 'Sms Api mobile number is invalid.');
        }
        elseif($result->getBody() == '-400')
        {
            abort(403, 'Sms Api language type is invalid.');
        }
        elseif($result->getBody() == '-500')
        {
            abort(403, 'Sms Api message has invalid characters.');
        }
        elseif($result->getBody() == '-600') {
            abort(403, 'Sms Api insufficient credit balance');
        }

        return [
            'message' => 'Successfully sent message.',
            'recipients' => $contacts
        ];
    }

    public function balance()
    {
        $http = new Client();

        $result = $http->get(config('app.sms_api') . 'bulkcredit.aspx', [
            'query' => [
                'apiusername' => config('app.sms_username'),
                'apipassword' => config('app.sms_password')
            ]
        ]);

        if($result->getbody() == '-100')
        {
            abort(403, 'Sms API invalid username or password.');
        }

        return [
            'sms_balance' => json_decode($result->getBody())
        ];
    }
}
