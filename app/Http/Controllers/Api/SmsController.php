<?php

namespace App\Http\Controllers\Api;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SmsController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'mobileno' => 'required',
            'message' => 'required'
        ]);

        /*if($request->mobileno == 'all')
        {
            // mobileno = Applicants::all()->contact
        }*/

        $http = new Client();

        $result = $http->get(config('app.sms_api') . 'api.aspx', [
            'query' => [
                'apiusername' => config('app.sms_username'),
                'apipassword' => config('app.sms_password'),
                'languagetype' => 1,
                'senderid' => config('app.sms_sender_id'),
                'mobileno' => $request->mobileno,
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

        return json_decode($result->getBody());
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
