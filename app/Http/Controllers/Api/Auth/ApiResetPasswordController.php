<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class ApiResetPasswordController extends Controller
{
    use ResetsPasswords;

    protected function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ];
    }

     public function sendResetResponse(Request $request, $response)
    {
        return ResponseBuilder::asSuccess(200)
            ->withMessage('Password reset successful.')
            ->build();
    }

    public function sendResetFailedResponse(Request $request, $response)
    {
        return ResponseBuilder::asError(401)
            ->withMessage(trans($response))
            ->withHttpCode(401)
            ->build();
    }
}
