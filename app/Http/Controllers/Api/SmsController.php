<?php

namespace App\Http\Controllers\Api;

use App\Sms;
use Carbon\Carbon;
use App\Applicant;
use App\Jobs\SendSms;
use App\SmsRecipient;
use GuzzleHttp\Client;
use App\Http\Requests\SmsRequest;
use App\Http\Controllers\Controller;
use App\Helpers\SearchFilterPagination;

class SmsController extends Controller
{
    public function index()
    {
        $query = Sms::query()->withCount('recipients');

        return SearchFilterPagination::get($query);
    }

    public function recipients(Sms $sms)
    {
        return SearchFilterPagination::get($sms->recipients()->getQuery());
    }

    public function send(SmsRequest $request)
    {
        $sms = Sms::create(request()
            ->merge(['user_id' => auth()->user()->id])
            ->only(Sms::getModel()->getFillable())
        );

        $contacts = Applicant::find(request('contacts'))->map(function ($applicant) use ($sms) {
            if ($applicant->contact_no === null || empty($applicant->contact_no)) {
                abort(400, $applicant->full_name . ' has no valid contact number.');
            }

            return [
                'applicant_id' => $applicant->id,
                'sms_id' => $sms->id,
                'status' => 'Sending to SMS Provider...'
            ];
        })->toArray();

        if (count($contacts) === 0)
        {
            abort(400, 'Cannot find contact number of selected applicants.');
        }

        $sms->recipients()->createMany($contacts);

        $sms->recipients()->each(function ($recipient) {
            if (request()->has('schedule')) {
                $delay = Carbon::now()->diffInSeconds(Carbon::parse(request('schedule')));
                SendSms::dispatch($recipient)->delay($delay);
            } else {
                SendSms::dispatch($recipient);
            }
        });

        return [
            'message' => 'SMS are now now being processed...'
        ];
    }

    public function server()
    {
        $http = new Client();

        $response = $http->get(config('services.itextmo.sms_api') . '/serverstatus.php', [
            'query' => [
                'apicode' => config('services.itextmo.sms_code')
            ]
        ]);

        return $response->getBody();
    }

    public function info()
    {
        $http = new Client();

        $response = $http->get(config('services.itextmo.sms_api') . '/apicode_info.php', [
            'query' => [
                'apicode' => config('services.itextmo.sms_code')
            ]
        ]);

        return $response->getBody();
    }

    public function pending()
    {
        $http = new Client();

        $response = $http->get(config('services.itextmo.sms_api') . '/display_outgoing.php', [
            'query' => [
                'apicode' => config('services.itextmo.sms_code'),
                'sortby' => "desc"
            ]
        ]);

        return $response->getBody();
    }
}
