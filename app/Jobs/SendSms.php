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
        $http = new Client();

        $params = [
            '1' => $this->recipient->contact,
            '2' => $this->recipient->sms->parsedSms($this->recipient->applicant),
            '3' => config('services.itextmo.sms_code')
        ];

        $response = $http->post(config('services.itextmo.sms_api') . '/api.php', [
            'form_params' => $params
        ]);

        $this->handleResponse($response);

    }

    protected function handleResponse(ResponseInterface $response)
    {
        $statusCode = $response->getBody();
        switch ($statusCode) {
            case "1":
                $this->invalidNumberResponse();
                break;
            case "2":
                $this->prefixNotSupportedResponse();
                break;
            case "3":
                $this->invalidApiCodeResponse();
                break;
            case "4":
                $this->maxMessageLimitResponse();
                break;
            case "5":
                $this->maxAllowedCharReachedResponse();
                break;
            case "6":
                $this->systemOfflineResponse();
                break;
            case "7":
                $this->expiredApiCodeResponse();
                break;
            case "8":
                $this->providerErrorResponse();
                break;
            case "9":
                $this->invalidParametersResponse();
                break;
            case "10":
                $this->numberIsBlockedFloodingResponse();
                break;
            case "11":
                $this->numberIsBlockedHardSendResponse();
                break;
            case "12":
                $this->cannotSetPrioritiesResponse();
                break;
            case "13":
                $this->invalidCustomSenderIdResponse();
                break;
            case "14":
                $this->invalidPreferredServerResponse();
                break;
            default:
                $this->smsSuccessResponse();
        }
    }

    public function failed(\Exception $exception)
    {
        Log::error($exception->getMessage(), [
            'statusCode' => $exception->getCode(),
            'recipient' => $this->recipient
        ]);
    }

    private function invalidNumberResponse()
    {
        $message = 'Invalid contact number.';

        $this->recipient->update([
            'status' => $message
        ]);
    }

    private function prefixNotSupportedResponse()
    {
        $message = 'Number prefix is not supported.';

        $this->recipient->update([
            'status' => $message
        ]);
    }

    private function invalidApiCodeResponse()
    {
        $message = 'Invalid Api Code.';

        $this->recipient->update([
            'status' => $message
        ]);
    }

    private function maxMessageLimitResponse()
    {
        $message = 'Maximun message limit has been reached.';

        $this->recipient->update([
            'status' => $message
        ]);
    }

    private function maxAllowedCharReachedResponse()
    {
        $message = 'Maximum allowed characters in message has been reached.';

        $this->recipient->update([
            'status' => $message
        ]);
    }

    private function systemOfflineResponse()
    {
        $message = 'System OFFLINE.';

        $this->recipient->update([
            'status' => $message
        ]);
    }

    private function expiredApiCodeResponse()
    {
        $message = 'ApiCode is expired.';

        $this->recipient->update([
            'status' => $message
        ]);
    }

    private function providerErrorResponse()
    {
        $message = 'Itextmo provider error. Please try again later.';

        $this->recipient->update([
            'status' => $message
        ]);
    }

    private function invalidParametersResponse()
    {
        $message = 'Invalid function parameters.';

        $this->recipient->update([
            'status' => $message
        ]);
    }

    private function numberIsBlockedFloodingResponse()
    {
        $message = 'Recipient\'s number is blocked due to FLOODING, message was ignored.';

        $this->recipient->update([
            'status' => $message
        ]);
    }

    private function numberIsBlockedHardSendResponse()
    {
        $message = 'Recipient\'s number is blocked temporarily due to HARD sending
            (after 3 retries of sending and message still failed to send) and the 
            message was ignored. Try again after an hour.';

        $this->recipient->update([
            'status' => $message
        ]);
    }

    private function cannotSetPrioritiesResponse()
    {
        $message = 'Invalid request. You can\'t set message priorities on non corporate apicodes.';

        $this->recipient->update([
            'status' => $message
        ]);
    }

    private function invalidCustomSenderIdResponse()
    {
        $message = 'Invalid or not registered custom sender ID.';

        $this->recipient->update([
            'status' => $message
        ]);
    }

    private function invalidPreferredServerResponse()
    {
        $message = 'Invalid preferred server number.';

        $this->recipient->update([
            'status' => $message
        ]);
    }

    private function smsSuccessResponse()
    {
        $this->recipient->update([
            'status' => 'Successfully sent to SMS Provider.'
        ]);

        Log::info('Sms sent to ' . $this->recipient->full_name, [
            'recipient' => $this->recipient,
            'message' => $this->recipient->sms->parsedSms($this->recipient->applicant)
        ]);
    }
}
