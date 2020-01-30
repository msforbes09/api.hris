<?php

namespace App\Http\Controllers\Api;

use App\Applicant;
use Carbon\Carbon;
use App\Jobs\SendSms;
use GuzzleHttp\Client;
use App\Http\Controllers\Controller;
use Psr\Http\Message\ResponseInterface;
use Illuminate\Database\Eloquent\Collection;

class SmsController extends Controller
{
    public function send()
    {
        request()->validate([
            'contacts' => 'required',
            'message' => 'required',
            'schedule' => 'date|after:' . Carbon::now()
        ]);

        $applicants = Applicant::find(request('contacts'));
        $balance = $this->getBalance();

        /*if($balance < $applicants->count())
        {
            abort(400, 'Sorry, you do not have enough balance. Your remaining balance is: ' . $balance);
        }*/

        $invalidContacts= [];

        foreach ($applicants as $applicant)
        {
            if(!preg_match('/63\d{10}/', $applicant->contact_no))
            {
                $invalidContacts[] = $applicant;
                continue;
            }

            $sms = [
                'contact' => $applicant->contact_no,
                'message' => request('message')

            ];

            $delay = Carbon::now()->diffInSeconds(Carbon::parse(request('schedule')));

            SendSms::dispatch($sms)
                ->delay(now()->addSeconds($delay));
        }

        if (count($invalidContacts) > 0) {
            return [
                'message' => count($invalidContacts) .' out of ' . $applicants->count() .' contact numbers are invalid.',
                'invalidContacts' => $invalidContacts
            ];
        }

        return [
            'message' => 'Successfully sent all sms'
        ];
    }

    public function balance()
    {
        $result = $this->getBalance();

        return [
            'sms_balance' => json_decode($result->getBody())
        ];
    }

    protected function getBalance()
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

        return json_decode($result->getBody());
    }
}
