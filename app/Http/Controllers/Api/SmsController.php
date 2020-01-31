<?php

namespace App\Http\Controllers\Api;

use App\Sms;
use App\Applicant;
use Carbon\Carbon;
use App\SmsStatus;
use App\Jobs\SendSms;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Psr\Http\Message\ResponseInterface;
use Illuminate\Database\Eloquent\Collection;

class SmsController extends Controller
{
    public function index()
    {
        return SmsStatus::with('sms')->paginate();
    }

    public function send()
    {
        request()->validate([
            'title' => 'required',
            'message' => 'required',
            'schedule' => 'date|after:' . Carbon::now(),
            'contacts' => 'required'
        ]);

        $applicants = Applicant::find(request('contacts'));
        $balance = $this->getBalance();

        /*if($balance < $applicants->count())
        {
            abort(400, 'Sorry, you do not have enough balance. Your remaining balance is: ' . $balance);
        }*/

        $invalidContacts= [];

        $sms = Sms::create([
            'user_id' => auth()->user()->id,
            'title' => request('title'),
            'message' => request('message'),
            'schedule' => request('schedule')
        ]);

        foreach ($applicants as $applicant)
        {
            if(!preg_match('/63\d{10}/', $applicant->contact_no))
            {
                $invalidContacts[] = $applicant;
                continue;
            }

            $delay = Carbon::now()->diffInSeconds(Carbon::parse(request('schedule')));

            $sms->statuses()->create([
                'applicant_id' => $applicant->id
            ]);

            SendSms::dispatch($sms, $applicant)
                ->delay(now()->addSeconds($delay));
        }

        Log::info(auth()->user()->username . ' - SMS Sent', [
            'data' => [
                'sms' => $sms,
                'contacts' => request('contacts')
            ]
        ]);

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

        Log::info(auth()->user()->username . ' - Checked SMS Balance', [
            'data' => [
                'balance' => $result->getBody()
            ]
        ]);

        if($result->getbody() == '-100')
        {
            abort(400, 'Sms API invalid username or password.');
        }

        return json_decode($result->getBody());
    }
}
