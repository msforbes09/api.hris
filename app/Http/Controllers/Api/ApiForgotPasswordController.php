<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ApiForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    public function sendResetLinkResponse(Request $request, $response)
    {
        return ResponseBuilder::asSuccess(200)
            ->withMessage('Password reset email sent.')
            ->build();
    }

    public function sendResetLinkFailedResponse(Request $request, $response)
    {
        return ResponseBuilder::asError(401)
            ->withMessage(trans($response))
            ->withHttpCode(401)
            ->build();
    }
}
