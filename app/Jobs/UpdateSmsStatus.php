<?php

namespace App\Jobs;

use Exception;
use App\SmsStatus;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateSmsStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;

    public function __construct()
    {
        //
    }

    public function handle()
    {
        $http = new Client();

        $statuses = SmsStatus::where('mt_id', '<>', null)
            ->orWhere('status', 'Message delivered to Telco')
            ->get();

        foreach ($statuses as $status)
        {
            $response = $http->get(config('app.sms_api') . 'bulktrx.aspx', [
                'query' => [
                    'mtid' => $status->mt_id
                ]
            ]);

            $this->handleResponseErrors($response, $status);
        }
    }

    protected function handleResponseErrors(ResponseInterface $response, SmsStatus $smsStatus)
    {
        if ($response->getBody() == '0')
        {
            $smsStatus->status = 'Success receive on mobile handset.';
            $smsStatus->save();
        }
        elseif ($response->getBody() == '100')
        {
            $smsStatus->status = 'Message delivered to Telco.';
            $smsStatus->save();
        }
        elseif ($response->getBody() == '-100')
        {
            $smsStatus->status = 'mtid invalid / not found.';
            $smsStatus->save();
        }
        elseif ($response->getBody() == '-200') {
            $smsStatus->status = 'Message sending fail.';
            $smsStatus->save();
        }
    }

    public function failed(Exception $exception)
    {
        Log::error($exception->getMessage());
    }

}
