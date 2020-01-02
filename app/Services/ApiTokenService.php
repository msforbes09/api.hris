<?php

namespace App\Services;

use GuzzleHttp\Client;
use App\Contracts\IUser;
use App\Contracts\IApiToken;
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
        // Return generated Token
        return $this->generateTokenData($credentials) ?
            $this->sendTokenResponse($this->token, $this->user) :
            $this->sendTokenFailResponse($this->error);
    }

    protected function isEmail($username)
    {
        return filter_var($username, FILTER_VALIDATE_EMAIL);
    }

    protected function getEmail($username)
    {
        $this->user = $this->iUser->getUserByUsername($username);
        
        return $this->user ? $this->user->email : $username;
    }

    protected function generateTokenData($credentials)
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

            $this->user = $this->isEmail($credentials->username) ? 
                $this->iUser->getUserByEmail($credentials->username) :
                $this->iUser->getUserByUsername($credentials->username);

            $this->token = json_decode($response->getbody());
            $this->token->user = $this->iUser->getUserWithType($this->user->id);

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