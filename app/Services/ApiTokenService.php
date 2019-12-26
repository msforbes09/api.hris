<?php

namespace App\Services;

use GuzzleHttp\Client;
use App\Contracts\IUser;
use App\Contracts\IApiToken;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class ApiTokenService implements IApiToken
{
    protected $token;
    protected $error;

    public function getToken($request)
    {
        // Validate
        $credentials = (Object) $request->validated();
        // Check if username or email is used
        // Get email if username is used
       if(!$this->isEmail($credentials->username))
       {
            $credentials->username = $this->getEmail($credentials->username);
       }
        // Request Token
        return $this->getTokenFromServer($credentials) ?
            $this->sendTokenResponse($this->token) :
            $this->sendTokenFailResponse($this->error);
    }

    protected function isEmail($username)
    {
        return filter_var($username, FILTER_VALIDATE_EMAIL);
    }

    protected function getEmail($username)
    {
        $user = IUser::getUserByUsername($username);

        return $user->email;
    }

    protected function getTokenFromServer($credentials)
    {
        try {
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

            $this->token = json_decode($response->getbody());

            return true;
        } catch (\Exception $e) {
            $this->error = $e;

            return false;
        }
    }

    protected function sendTokenResponse($data)
    {
        return ResponseBuilder::asSuccess(200)
            ->withData((array) $data)
            ->withHttpCode(200)
            ->build();
    }

    protected function sendTokenFailResponse($error)
    {
        return ResponseBuilder::asError($error->getCode())
            ->withMessage('Invalid username or password.')
            ->withHttpCode($error->getCode())
            ->build();
    }

    public function removeTokens($user)
    {
        $user->tokens->each(function($token, $key) {
            $token->delete();
        });

        return ResponseBuilder::asSuccess(200)->build();
    }
}