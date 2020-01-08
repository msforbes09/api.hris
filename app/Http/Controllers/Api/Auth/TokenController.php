<?php

namespace App\Http\Controllers\Api\Auth;

use App\User;
use GuzzleHttp\Client;
use App\Contracts\IApiToken;
use Illuminate\Http\Request;
use App\Http\Requests\TokenRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Exception\ClientException;

class TokenController extends Controller
{
    public function user()
    {
        $user = Auth::user();


    }

    public function get(TokenRequest $request)
    {   
        $data = $request->validated();


        $user = User::where('email', $data['username'])
                    ->orWhere('username', $data['username'])
                    ->first();


        if($user != NULL)
        {
            try
            {
                $http = new Client();

                $response = $http->post(route('passport.token'), [
                    'form_params' => [
                        'grant_type' => 'password',
                        'client_id' => config('passport.password_grant_id'),
                        'client_secret' => config('passport.password_grant_secret'),
                        'username' => $user->email,
                        'password' => $data['password'],
                        'scope' => ''
                    ]
                ]);


                return response()->json([
                    'message' => __('auth.received'),
                    'data' => json_decode($response->getBody())
                ]);
            }
            catch(\Exception $e)
            {
                if($e instanceof ClientException)
                {
                    abort(400, __('auth.oops'));
                }


                abort(422, __('auth.incorrect'));
            }
        }


        abort(422, __('auth.incorrect'));
    }

    public function remove(User $user)
    {
       $user->tokens->each(function($token, $key) 
       {
            $token->delete();
       });


        Log::info(__('auth.log', [
            'name' => $user->name, 
            'id' => $user->id,
            'action' => 'logged out.'
        ]));


       return response()->json([
            'message' => 'User successfully logged out.'
       ]);
    }
}
