<?php

namespace App\Http\Controllers\Api;

use App\Sms;
use Carbon\Carbon;
use App\Applicant;
use App\Jobs\SendSms;
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

    public function send(SmsRequest $request)
    {
        $sms = Sms::create(request()
            ->merge(['user_id' => auth()->user()->id])
            ->only(Sms::getModel()->getFillable())
        );

        $contacts = Applicant::find(request('contacts'))->map(function ($applicant) use ($sms) {
            return [
                'applicant_id' => $applicant->id,
                'sms_id' => $sms->id,
                'status' => 'Sent to SMS API provider'
            ];
        })->toArray();

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
            'message' => 'Sms sent to applicants.'
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
