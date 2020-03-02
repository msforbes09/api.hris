<?php

namespace App\Http\Controllers\Api;

use App\Module;
use App\SmsTemplate;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\SmsTemplateRequest;

class SmsTemplateController extends Controller
{
    protected $module;

    public function __construct()
    {
        $this->module = Module::where('code', 'sms_template')->first();
    }

    public function index()
    {
        $this->authorize('allows', [$this->module, 'view']);

        return SmsTemplate::orderBy('id', 'desc')->get();
    }

    public function store(SmsTemplateRequest $request)
    {
        $this->authorize('allows', [$this->module, 'create']);

        $template = SmsTemplate::create(request()->only(SmsTemplate::getModel()->getFillable()));

        Log::info(auth()->user()->username . ' has created an Sms Template.', ['data' => $template]);

        return [
            'message' => 'Successfully created template.',
            'data' => $template
        ];
    }

    public function show(SmsTemplate $template)
    {
        $this->authorize('allows', [$this->module, 'show']);

        return $template;
    }

    public function update(SmsTemplateRequest $request, SmsTemplate $sms_template)
    {
        $this->authorize('allows', [$this->module, 'update']);

        $sms_template->fill(request()->only($sms_template->getFillable()))->save();

        Log::info(auth()->user()->username . ' has updated an Sms Template.', ['data' => $sms_template]);

        return [
            'message' => 'Successfully updated template.',
            'data' => $sms_template
        ];
    }

    public function destroy(SmsTemplate $sms_template)
    {
        $this->authorize('allows', [$this->module, 'delete']);

        $sms_template->delete();

        Log::info(auth()->user()->username . ' has deleted an Sms Template.', ['data' => $sms_template]);

        return [
            'message' =>'Successfully deleted template.',
        ];
    }
}
