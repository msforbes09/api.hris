<?php

namespace App\Jobs;

use App\Sms;
use Exception;
use App\Applicant;
use App\Helpers\SmsGateway;
use Illuminate\Bus\Queueable;
use App\Helpers\OneWaySmsProvider;
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
    private $gateway;
    private $applicant;

    public function __construct(SmsGateway $gateway, Applicant $applicant)
    {
        $this->gateway = $gateway;
        $this->applicant = $applicant;
    }

    public function handle()
    {
        $this->gateway->sms->statuses()->find($this->gateway->sms->id)->update([
            'mt_id' => $this->gateway->send($this->applicant)
        ]);
    }
}
