<?php

namespace App\Jobs;

use App\SmsRecipient;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
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
        $http = new Client();

        $params = [
            '1' => $this->recipient->contact,
            '2' => $this->recipient->sms->parsedSms($this->recipient->applicant),
            '3' => config('services.itextmo.sms_code')
        ];

        $response = $http->post(config('services.itextmo.sms_api') . '/api.php', [
            'form_params' => $params
        ]);

        Log::info('Sms sent to ' . $this->recipient->full_name, [
            'statusCode' => json_decode($response->getBody()),
            'params' => $params
        ]);
    }
}
