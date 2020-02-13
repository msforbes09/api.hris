<?php

namespace App\Http\Controllers\Api;

use App\Sms;
use App\Module;
use App\Applicant;
use Carbon\Carbon;
use App\SmsStatus;
use App\Jobs\SendSms;
use GuzzleHttp\Client;
use App\Http\Requests\SmsRequest;
use App\Helpers\OneWaySmsProvider;
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

    public function send(SmsRequest $request)
    {
        $this->authorize('send', $this->module);

        $sms = new OneWaySmsProvider();

        $balance = $sms->balance();

        /*if($balance < $applicants->count())
        {
            abort(400, 'Sorry, you do not have enough balance. Your remaining balance is: ' . $balance);
        }*/

        $applicants = Applicant::find(request('contacts'));

        $sms->createSms([
            'user_id' => auth()->user()->id,
            'title' => request('title'),
            'message' => request('message'),
            'schedule' => request('schedule')
        ]);

        $invalidContacts = $sms->toApplicants($applicants);

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
