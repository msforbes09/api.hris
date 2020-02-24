<?php

namespace App\Http\Controllers\Api;


use App\Applicant;
use App\SmsTemplate;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\SmsTemplateRequest;

class SmsTemplateController extends Controller
{
    public function index()
    {
        return SmsTemplate::orderBy('id', 'desc')->get();
    }

    public function store(SmsTemplateRequest $request)
    {
        $template = SmsTemplate::create(request()->only(SmsTemplate::getModel()->getFillable()));

        Log::info(auth()->user()->username . ' has created an Sms Template.', ['data' => $template]);

        return [
            'message' => 'Successfully created template.',
            'data' => $template
        ];
    }

    public function show(SmsTemplate $template)
    {
        return $template;
    }

    public function update(SmsTemplateRequest $request, SmsTemplate $template)
    {
        $template->fill(request()->only($template->getFillable()))->save();

        Log::info(auth()->user()->username . ' has updated an Sms Template.', ['data' => $template]);

        return [
            'message' => 'Successfully updated template.',
            'data' => $template
        ];
    }

    public function destroy(SmsTemplate $template)
    {
        $template->delete();

        Log::info(auth()->user()->username . ' has deleted an Sms Template.', ['data' => $template]);

        return [
            'message' =>'Successfully deleted template.',
        ];
    }
}
