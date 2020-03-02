<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use App\Http\Requests\TokenRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{

    /**
     * Return authenticated user
     *
     * @return mixed
     */
    public function user()
    {
        return request()
            ->user()
            ->load('userType.moduleActions');
    }

    /**
     *  Create access token for user
     *
     * @param TokenRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createToken(TokenRequest $request)
    {
        $user = $this->userExists($request->get('username'));

        if (!$user)
            return $this->failedUsernameResponse();

        if (!auth()->attempt( $this->credentials($request) ))
            return $this->failedPasswordResponse();

        $accessToken = $user->createToken('App Token')->accessToken;

        return $this->createAccessTokenResponse($user, $accessToken);
    }

    /**
     * Remove all access token of user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeTokens()
    {
        $user = request()->user();

        if (count($user->tokens) > 0) {
            $user->tokens->each(function (\Laravel\Passport\Token $token) {
                $token->delete();
            });
        }

        return $this->removeTokensResponse($user);
    }

    /**
     * Send remove tokens response to client
     *
     * @param $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeTokensResponse($user) {
        Log::info($user->username . ' has logged out.');

        return response()
            ->json(
                ['message' => 'User successfully logged out.']
            );
    }

    /**
     * Return App\User if user it exists
     * otherwise, return false
     *
     * @param $username
     * @return mixed
     */
    private function userExists($username)
    {
        $usernameType = $this->getUsernameType($username);

        return \App\User
            ::where($usernameType, request('username'))
            ->first()
            ?: false;

    }

    /**
     * Return type of username used
     *
     * @param $username
     * @return string
     */
    private function getUsernameType($username)
    {
        return filter_var($username, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'username';
    }

    /**
     * Rerturn credentials used in request
     *
     * @param TokenRequest $request
     * @return array
     */
    private function credentials(TokenRequest $request)
    {
        $usernameType = $this->getUsernameType($request->get('username'));

        return [
            $usernameType => $request->get('username'),
            'password' => $request->get('password')
        ];
    }

    /**
     * Send access token response to client
     *
     * @param $user
     * @param $accessToken
     * @return \Illuminate\Http\JsonResponse
     */
    private function createAccessTokenResponse($user, $accessToken)
    {
        Log::info($user->username . ' has logged in.');

        return response()
                ->json(
                    [
                        'message' => __('auth.received'),
                        'user' => $user,
                        'accessToken' => $accessToken
                    ]
                );
    }

    /**
     *  Send username fail response to client
     *
     * @return \Illuminate\Http\JsonResponse
     */
    private function failedUsernameResponse()
    {
        return response()
            ->json(
                [
                    'message' => __('auth.incorrect'),
                    'errors' => [
                        'username' => [__('auth.failed', ['field' => 'username'])]
                    ]
                ],
                422
            );
    }

    /**
     * Send password fail response to client
     *
     * @return \Illuminate\Http\JsonResponse
     */
    private function failedPasswordResponse()
    {
        return response()
            ->json(
                [
                    'message' => __('auth.incorrect'),
                    'errors' => [
                        'password' => [__('auth.failed', ['field' => 'password'])]
                    ]
                ],
                422
            );
    }
}
