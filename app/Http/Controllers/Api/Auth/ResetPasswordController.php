<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
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
        return response()->json([
            'message' => 'Password reset successfully.'
        ]);
    }

    public function sendResetFailedResponse(Request $request, $response)
    {
        return response()->json([
            'message' => trans($response)
        ], 422);
    }
}
