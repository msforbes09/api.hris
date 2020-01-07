<?php

namespace App\Services;

use GuzzleHttp\Client;
use App\Contracts\IUser;
use App\Contracts\IApiToken;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class ApiTokenService implements IApiToken
{
    protected $user;
    protected $token;
    protected $error;
    protected $iUser;

    public function __construct(IUser $iUser)
    {
        $this->iUser = $iUser;
    }

    public function user()
    {
        $user =  Auth::user();
        $user->user_type;

        return ResponseBuilder::asSuccess(200)
            ->withMessage(__('auth.success', ['name' => $user->name]))
            ->withData(['user' => $user])
            ->build();
    }

    public function getToken($request)
    {
        // Validate
        $credentials = (Object) $request->validated();
        // Set User
       $this->setUser($credentials);
        // Return generated Token
        return $this->generateTokenData($credentials->password) ?
            $this->sendTokenResponse($this->token) :
            $this->sendTokenFailResponse($this->error);
    }

    protected function isEmail($username)
    {
        return filter_var($username, FILTER_VALIDATE_EMAIL);
    }

    protected function setUser($credentials)
    {
        $this->user =  $this->isEmail($credentials->username) 
            ? $this->iUser->getByEmail($credentials->username)
            : $this->iUser->getByUsername($credentials->username);
    }

    protected function generateTokenData($password)
    {
        try {
            $http = new Client();

            $response = $http->post(route('passport.token'), [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => config('passport.password_grant_id'),
                    'client_secret' => config('passport.password_grant_secret'),
                    'username' => $this->user->email,
                    'password' => $password,
                    'scope' => ''
                ],
            ]);

            $this->token = json_decode($response->getbody());

            Log::info(__('auth.log', [
                'name' => $this->user->name, 
                'id' => $this->user->id,
                'action' => 'logged in.'
            ]));

            return true;
        } catch (\Exception $e) {
            $this->error = $e;

            return false;
        }
    }

    protected function sendTokenResponse($data)
    {
        return ResponseBuilder::asSuccess(200)
            ->withMessage(__('auth.received'))
            ->withData((array) $data)
            ->withHttpCode(200)
            ->build();
    }

    protected function sendTokenFailResponse($error)
    {
        return ResponseBuilder::asError($error->getCode())
            ->withMessage(__('auth.failed'))
            ->withHttpCode($error->getCode())
            ->build();
    }

    public function removeTokens($user)
    {
        $user->tokens->each(function($token, $key) {
            $token->delete();
        });

        Log::info(__('auth.log', [
                'name' => $this->user->name, 
                'id' => $this->user->id,
                'action' => 'logged out.'
            ]));

        return ResponseBuilder::asSuccess(200)->build();
    }
}