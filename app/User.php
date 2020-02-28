<?php

namespace App;

use GuzzleHttp\Client;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Log;
use Illuminate\Notifications\Notifiable;
use OwenIt\Auditing\Contracts\Auditable;
use App\Notifications\WelcomeNotification;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements Auditable
{
    use HasApiTokens, Notifiable, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'user_type_id', 'branch_id', 'name', 'username', 'email', 'password', 'active',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function isActive()
    {
        return (bool) $this->active;
    }

    public function userType()
    {
        return $this->belongsTo('App\UserType');
    }

    public function allowedModuleActions()
    {
        return $this->userType->moduleActions;
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token, $this->email));
    }

    public function sendWelcomeNotification($password)
    {
        $this->notify(new WelcomeNotification($this, $password));
    }

    public function accessToken($credentials)
    {
        $http = new Client();
        $accessToken = [];

        try
        {
            $response = $http->post(route('passport.token'), [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => config('passport.password_grant_id'),
                    'client_secret' => config('passport.password_grant_secret'),
                    'username' => $this->email,
                    'password' => $credentials['password'],
                    'scope' => ''
                ]
            ]);

            $accessToken = json_decode($response->getBody());
        }
        catch(\Exception $e)
        {
            Log::error($e->getMessage(), ['line' => $e->getLine(), 'file' => $e->getFile()]);

            abort(400, __('auth.oops'));
        }

        return $accessToken;
    }

    function removeTokens()
    {
        try
        {
            $this->tokens->each(function($token, $key)
            {
                $token->delete();
            });
        }
        catch (\Exception $e)
        {
            Log::error($e->getMessage(), ['line' => $e->getLine(), 'file' => $e->getFile()]);

            abort(400, 'Removing user tokens failed.');
        }
    }
}
