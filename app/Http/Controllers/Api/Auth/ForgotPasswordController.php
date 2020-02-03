<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    public function sendResetLinkResponse(Request $request, $response)
    {
        Log::info(request('email') . ' requested a password reset link.');

        return response()->json([
            'message' => 'Password reset email sent.'
        ]);
    }

    public function sendResetLinkFailedResponse(Request $request, $response)
    {
        return response()->json([
            'message' => trans($response),
            'errors' => ['email' => [trans($response)]]
        ], 422);
    }
}
