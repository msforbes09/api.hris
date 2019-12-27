<?php

namespace App\Http\Controllers\Api\Auth;

use App\Contracts\IApiToken;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ApiTokenRequest;

class ApiTokenController extends Controller
{
    protected $apiToken;

    public function __construct(IApiToken $apiToken)
    {
        $this->apiToken = $apiToken;
    }

    public function getToken(ApiTokenRequest $request)
    {   
        return $this->apiToken->getToken($request);
    }

    public function removeToken()
    {
       return $this->apiToken->removeTokens(Auth::user());
    }
}
