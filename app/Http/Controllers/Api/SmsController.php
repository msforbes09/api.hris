<?php

namespace App\Http\Controllers\Api;

use App\Sms;
use App\Module;
use App\Applicant;
use Carbon\Carbon;
use App\SmsStatus;
use App\Jobs\SendSms;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class SmsController extends Controller
{
    protected $module;

    public function __construct()
    {
        $this->module = Module::where('code', 'sms')->first();
    }

    public function index()
    {
        $this->authorize('view', $this->module);

        return SmsStatus::with('sms')->paginate();
    }

    public function send()
    {
        $this->authorize('send', $this->module);

        request()->validate([
            'title' => 'required',
            'message' => 'required',
            'schedule' => 'date|after:' . Carbon::now(),
            'contacts' => 'required'
        ]);

        $applicants = Applicant::find(request('contacts'));
        $balance = $this->requestSmsBalance();

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

            $parsedMessage = $this->parseSmsMessage($sms['message'], $applicant);

            $sms->statuses()->create([
                'applicant_id' => $applicant->id
            ]);

            SendSms::dispatch($sms, $applicant)
                ->delay(now()->addSeconds($delay));

            Log::info(auth()->user()->username . ' has sent an SMS.', [
                'data' => [
                    'recipient' => $applicant->only(['id', 'last_name', 'first_name', 'middle_name', 'contact_no']),
                    'sms' => $sms,
                    'parsedMessage' => $parsedMessage
                ]
            ]);
        }

        if (count($invalidContacts) > 0) {
            $warning = count($invalidContacts) .' out of ' . $applicants->count() .' contact numbers are invalid.';

            Log::warning($warning, [
                'contacts' => request('contacts'),
                'invalid' => $invalidContacts
            ]);

            return [
                'message' => $warning,
                'invalidContacts' => $invalidContacts
            ];
        }

        return [
            'message' => 'Successfully sent all sms'
        ];
    }

    public function balance()
    {
        $balance = $this->requestSmsBalance();

        Log::info(auth()->user()->username . ' has checked the SMS Balance', [
            'data' => [
                'balance' => $balance
            ]
        ]);

        return [
            'sms_balance' => $balance
        ];
    }

    protected function requestSmsBalance()
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
            abort(400, 'Sms API invalid username or password.');
        }

        return json_decode($result->getBody());
    }

    protected function parseSmsMessage($message, $applicant)
    {
        $parsedSms = $message;

        foreach ($applicant->getAttributes() as $key => $value)
        {
            $parsedSms = str_replace('~'.$key.'~', $value, $parsedSms);
        }

        return $parsedSms;
    }
}
