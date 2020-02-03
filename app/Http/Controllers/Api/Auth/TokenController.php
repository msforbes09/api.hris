<?php

namespace App\Http\Controllers\Api\Auth;

use App\User;
use App\Http\Requests\TokenRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class TokenController extends Controller
{
    public function user()
    {
        return auth()->user()->load('userType.moduleActions');
    }

    public function get(TokenRequest $request)
    {
        $field = filter_var(request('username'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $user = User::where($field, request('username'))->first();

        if ($user == null)
        {
            return $this->failedUsernameResponse();
        }

        $credentials = [$field => request('username'), 'password' => request('password')];

        if (!auth()->attempt($credentials))
        {
            return $this->failedPasswordResponse();
        }

        $accessToken = $user->accessToken($credentials);

        Log::info(auth()->user()->username . ' has logged in.');

        return response()->json([
            'message' => __('auth.received'),
            'token' => $accessToken
        ]);
    }

    public function remove()
    {
        auth()->user()->removeTokens();

        Log::info(auth()->user()->username . ' has logged out.');

        return response()->json([
            'message' => 'User successfully logged out.'
        ]);
    }

    protected function failedUsernameResponse()
    {
        return response()->json([
            'message' => __('auth.incorrect'),
            'errors' => [
                'username' => [__('auth.failed', ['field' => 'username'])]
            ]
        ], 422);
    }

    protected function failedPasswordResponse()
    {
        return response()->json([
            'message' => __('auth.incorrect'),
            'errors' => [
                'username' => [__('auth.failed', ['field' => 'username'])]
            ]
        ], 422);
    }
}
