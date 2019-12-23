<?php

namespace App\Http\Controllers\Api;

use App\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ApiTokenRequest;

class ApiTokenController extends Controller
{
    public function getToken(ApiTokenRequest $request)
    {   
        // Validate
        $credentials = $request->validated();
        // Check if username or email is used
        $credentials = $this->convertUsernameToEmail((Object) $credentials);
        // Request Token
        $response = $this->requestTokenFromServer($credentials);

        return response()->json([ $response ]);
    }

    public function removeToken()
    {
       Auth::user()->tokens->each(function($token, $key) {
            $token->delete();
        });

        return response()->json(['message' => 'User tokens removed successfully.']);
    }

    protected function convertUsernameToEmail($credentials)
    {
        $user = User::where('username', $credentials->username)->first();

        $login_type = filter_var($credentials->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if($login_type === 'username' && $user !== null) {
           $credentials->username = $user->email;
        }

        return $credentials;
    }

    protected function requestTokenFromServer($credentials)
    {
        $http = new Client();

        $response = $http->post(route('passport.token'), [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => config('passport.password_grant_id'),
                'client_secret' => config('passport.password_grant_secret'),
                'username' => $credentials->username,
                'password' => $credentials->password,
                'scope' => ''
            ],
        ]);

        return json_decode($response->getBody());
    }
}
