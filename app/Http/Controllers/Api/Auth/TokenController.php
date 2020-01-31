<?php

namespace App\Http\Controllers\Api\Auth;

use App\User;
use GuzzleHttp\Client;
use App\Contracts\IApiToken;
use Illuminate\Http\Request;
use App\Http\Requests\TokenRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;

class TokenController extends Controller
{
    public function user()
    {
        $user = auth()->user();

        $user->userType->moduleActions;

       return $user;
    }

    public function get(TokenRequest $request)
    {
        $user = findUser(request('username'));

        if($user != NULL && $user->isActive())
        {
            if(auth()->attempt(['email' => $user->email, 'password' => request('password')]))
            {
                return $this->generate($user->email, request('password'));
            }

            return response()->json([
                'message' => __('auth.incorrect'),
                'errors' => [
                    'password' => [__('auth.failed', ['field' => 'password'])]
                ]
            ], 422);
        }

        return response()->json([
            'message' => __('auth.incorrect'),
            'errors' => [
                'username' => [__('auth.failed', ['field' => 'username'])]
            ]
        ], 422);
    }

    public function remove(User $user)
    {
       removeTokens(auth()->user());

        Log::info(auth()->user()->username . ' - Logged Out', [
            'data' => auth()->user()
        ]);

       return response()->json([
            'message' => 'User successfully logged out.'
       ]);
    }

    protected function generate($username, $password)
    {
         try
        {
            $http = new Client();

            $response = $http->post(route('passport.token'), [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => config('passport.password_grant_id'),
                    'client_secret' => config('passport.password_grant_secret'),
                    'username' => $username,
                    'password' => $password,
                    'scope' => ''
                ]
            ]);

            Log::info(auth()->user()->username . ' - Logged In', [
                'data' => auth()->user()
            ]);

            return response()->json([
                'message' => __('auth.received'),
                'token' => json_decode($response->getBody())
            ]);
        }
        catch(\Exception $e)
        {
            abort(400, __('auth.oops'));
        }
    }
}
