<?php

namespace App\Http\Controllers\Api;

use App\Sms;
use App\Module;
use App\Applicant;
use Carbon\Carbon;
use App\Jobs\SendSms;
use App\SmsRecipient;
use GuzzleHttp\Client;
use App\Http\Requests\SmsRequest;
use App\Http\Controllers\Controller;
use App\Helpers\SearchFilterPagination;

class SmsController extends Controller
{
    protected $module;

    public function __construct()
    {
        $this->module = Module::where('code', 'sms')->first();
    }

    public function index()
    {
        $this->authorize('allows', [$this->module, 'view']);

        $query = Sms::query()->withCount('recipients');

        return SearchFilterPagination::get($query);
    }

    public function recipients(Sms $sms)
    {
        $this->authorize('allows', [$this->module, 'view']);

        return SearchFilterPagination::get($sms->recipients()->getQuery());
    }

    public function send(SmsRequest $request)
    {
        $this->authorize('allows', [$this->module, 'send']);

        $sms = Sms::create(request()
            ->merge(['user_id' => auth()->user()->id])
            ->only(Sms::getModel()->getFillable())
        );

        $contacts = Applicant::find($request->get('contacts'))
            ->map(function ($applicant) use ($sms) {
                if ($applicant->contact_no === null || empty($applicant->contact_no)) {
                    abort(400, $applicant->full_name . ' has no valid contact number.');
                }

                return [
                    'applicant_id' => $applicant->id,
                    'sms_id' => $sms->id,
                    'status' => 'Sending to SMS Provider...'
                ];
            })->toArray();

        if (count($contacts) === 0) {
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
            'message' => 'Message are now being sent to recipients...'
        ];
    }
}