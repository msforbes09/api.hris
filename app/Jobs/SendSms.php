<?php

namespace App\Jobs;

use App\Sms;
use Exception;
use App\Applicant;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;
    protected $sms;
    protected $applicant;

    public function __construct(Sms $sms, Applicant $applicant)
    {
        $this->sms = $sms;
        $this->applicant = $applicant;
    }

    public function handle()
    {
        $http = new Client();

        $response = $http->get(config('app.sms_api') . 'api.aspx', [
            'query' => [
                'apiusername' => config('app.sms_username'),
                'apipassword' => config('app.sms_password'),
                'languagetype' => 1,
                'senderid' => config('app.sms_sender_id'),
                'mobileno' => $this->applicant->contact_no,
                'message' => $this->sms->message
            ]
        ]);

        $mtId = $this->handleResponseErrors($response);

        $this->sms->statuses()->find($this->sms->id)->update([
            'mt_id' => $mtId
        ]);
    }

    protected function handleResponseErrors(ResponseInterface $reponse)
    {
        if($reponse->getbody() == '-100')
        {
            throw new Exception('Sms API invalid username or password.');
        }
        elseif($reponse->getBody() == '-200')
        {
            throw new Exception('Sms Api sender id is invalid.');
        }
        elseif($reponse->getBody() == '-300')
        {
            throw new Exception('Sms Api mobile number is invalid.');
        }
        elseif($reponse->getBody() == '-400')
        {
            throw new Exception('Sms Api language type is invalid.');
        }
        elseif($reponse->getBody() == '-500')
        {
            throw new Exception('Sms Api message has invalid characters.');
        }
        elseif($reponse->getBody() == '-600') {
            throw new Exception('Sms Api insufficient credit balance');
        }

        return $reponse->getBody();
    }

    public function failed(Exception $exception)
    {
        $this->sms->statuses()->find($this->sms->id)->update([
            'status' => $exception->getMessage()
        ]);

        Log::error($exception->getMessage());
    }
}
