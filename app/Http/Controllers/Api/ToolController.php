<?php

namespace App\Http\Controllers\Api;

use App\Key;
use App\User;
use App\Branch;
use App\Client;
use App\Module;
use App\Company;
use App\UserType;
use App\SmsTemplate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ToolController extends Controller
{
    public function userManagement()
    {
        return [
            'user_types' => UserType::all(),
            'branches' => Branch::all()
        ];
    }

    public function moduleManagement()
    {
        return [
            'modules' => Module::with('moduleActions')->get()
        ];
    }

    public function clientManagement()
    {
        return [
            'companies' => Company::all()
        ];
    }
    public function applicantManagement()
    {
        return [
            'keys' => Key::with('keywords')->get(),
            'clients' => Client::with('branches')->with('positions')->get()
        ];
    }

    public function smsManagement()
    {
        return [
            'sms_templates' => SmsTemplate::all()
        ];
    }
}
