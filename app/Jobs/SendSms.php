<?php

namespace App\Jobs;

use App\SmsRecipient;
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
    private $recipient;

    public function __construct(SmsRecipient $recipient)
    {
        $this->recipient = $recipient;
    }

    public function handle()
    {
        $client = new \SoapClient(config('services.netplay.sms_api'));

        $response = $client->__soapCall('SendSMS', array(
            'SendSMS' => array(
                'username' => config('services.netplay.sms_username'),
                'password' => config('services.netplay.sms_password'),
                'source' => config('services.netplay.sms_sender_id'),
                'destination' => $this->recipient->contact,
                'message' => $this->recipient
                    ->sms
                    ->parsedSms($this->recipient->applicant),
                'route_id' => 0
            )
        ));

        if($response->code != 1000) {
            throw  new \Exception(
                'SMS has not been sent to reciepient.',
                $response->code
            );
        }

        $this->recipient->status = $response->status;
        $this->recipient->mid = $response->messageId;
        $this->recipient->save();

        Log::info('An SMS has been processed.', [
            'result' => $response
        ]);
    }
}
