<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Events\messengerCallbackEvent;

class FbWebhookController extends Controller
{
    public function index()
    {
        return request('hub_challenge');
    }

    public function event()
    {
        event(new messengerCallbackEvent(request()->toArray()));
    }
}
