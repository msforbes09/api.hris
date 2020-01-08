<?php

namespace App\Services;

use GuzzleHttp\Client;
use App\Contracts\IUser;
use App\Contracts\IApiToken;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Exception\ServerException;
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
        //Lookup User
        if($this->lookUpUser($credentials) === null){
            return $this->sendTokenFailResponse(__('auth.incorrect'));
        }
        // Return generated Token
        return $this->generateTokenData($credentials->password);
    }

    protected function isEmail($username)
    {
        return filter_var($username, FILTER_VALIDATE_EMAIL);
    }

    protected function lookUpUser($credentials)
    {
        $this->user =  $this->isEmail($credentials->username) 
            ? $this->iUser->getByEmail($credentials->username)
            : $this->iUser->getByUsername($credentials->username);

        return $this->user;
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

            Log::info(__('auth.log', [
                'name' => $this->user->name, 
                'id' => $this->user->id,
                'action' => 'logged in.'
            ]));

            return $this->sendTokenResponse(json_decode($response->getbody()));
        } catch (\Exception $e) {
            if($e instanceof ServerException)
            {
                return $this->sendTokenFailResponse(__('auth.oops'));
            }

            return $this->sendTokenFailResponse(__('auth.incorrect'));
        }
    }

    protected function sendTokenResponse($data)
    {
        return ResponseBuilder::asSuccess(200)
            ->withMessage(__('auth.received'))
            ->withData((array) $data)
            ->build();
    }

    protected function sendTokenFailResponse($message)
    {
        return ResponseBuilder::asError(422)
            ->withMessage($message)
            ->withHttpCode(422)
            ->build();
    }

    public function removeTokens($user)
    {
        $user->tokens->each(function($token, $key) {
            $token->delete();
        });

        Log::info(__('auth.log', [
                'name' => $user->name, 
                'id' => $user->id,
                'action' => 'logged out.'
            ]));

        return ResponseBuilder::asSuccess(200)->build();
    }
}