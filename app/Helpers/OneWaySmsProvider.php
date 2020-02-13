<?php


namespace App\Helpers;


use App\Sms;
use Carbon\Carbon;
use App\Jobs\SendSms;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface;

class OneWaySmsProvider implements SmsGateway
{
    public $sms;
    private const INVALID_USER_PASS = '-100';
    private const INVALID_SENDER_ID = '-200';
    private const INVALID_MOBILE_NUMBER = '-300';
    private const INVALID_LANGUAGE_TYPE = '-400';
    private const INVALID_MESSAGE_CHARACTERS = '-500';
    private const INSUFFICIENT_CREDITS = '-600';

    public function createSms($sms)
    {
        $this->sms = Sms::create($sms);
    }

    public function toApplicants($applicants)
    {
        $invalidContacts = [];

        foreach ($applicants as $applicant)
        {
            if(!preg_match('/63\d{10}/', $applicant->contact_no))
            {
                $invalidContacts[] = $applicant;
                continue;
            }

            $delay = Carbon::now()->diffInSeconds(Carbon::parse(request('schedule')));

            SendSms::dispatch($this)->delay(now()->addSeconds($delay));

            $this->sms->statuses()->create([
                'applicant_id' => $applicant->id
            ]);
        }

        return $invalidContacts;
    }

    public function send($applicant)
    {
        $http = new Client();
        $parsedMessage = $this->parseSmsMessage($this->sms->message, $applicant);

        $response = $http->get(config('app.sms_api') . 'api.aspx', [
            'query' => [
                'apiusername' => config('app.sms_username'),
                'apipassword' => config('app.sms_password'),
                'languagetype' => 1,
                'senderid' => config('app.sms_sender_id'),
                'mobileno' => $applicant->contact_no,
                'message' => $parsedMessage
            ]
        ]);

        return $this->handleResponse($response, $applicant, $parsedMessage);
    }

    public function balance()
    {
        $http = new Client();

        $response = $http->get(config('app.sms_api') . 'bulkcredit.aspx', [
            'query' => [
                'apiusername' => config('app.sms_username'),
                'apipassword' => config('app.sms_password')
            ]
        ]);

        return $this->handleResponse($response);
    }

    private function handleResponse(ResponseInterface $response, $applicant = null, $parsedMessage = null)
    {
        $body = $response->getBody();

        if ($body == self::INVALID_USER_PASS)
        {
            abort(400, 'Invalid api username or password.');
        }

        if ($body == self::INVALID_SENDER_ID)
        {
            abort(400, 'Invalid api sender id.');
        }

        if ($body == self::INVALID_MOBILE_NUMBER)
        {
            abort(400, 'Invalid mobile number in request.');
        }

        if ($body == self::INVALID_LANGUAGE_TYPE)
        {
            abort(400, 'Invalid api language type.');
        }

        if ($body == self::INVALID_MESSAGE_CHARACTERS)
        {
            abort(400, 'Invalid characters in message found.');
        }

        if ($body == self::INSUFFICIENT_CREDITS)
        {
            abort(400, 'Insufficient credit balance.');
        }

        Log::info(auth()->user()->username . ' has sent an SMS.', [
            'data' => [
                'recipient' => $applicant->only(['id', 'last_name', 'first_name', 'middle_name', 'contact_no']),
                'sms' => $this->sms,
                'parsedMessage' => $parsedMessage,
                'mt_id' => $body
            ]
        ]);

        return $body;
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
