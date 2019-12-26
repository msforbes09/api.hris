<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use App\Services\ApiTokenService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ApiTokenRequest;

class ApiTokenController extends Controller
{
    protected $apiTokenService;

    public function __construct(ApiTokenService $apiTokenService)
    {
        $this->apiTokenService = $apiTokenService;
    }

    public function getToken(ApiTokenRequest $request)
    {   
        return $this->apiTokenService->getToken($request);
    }

    public function removeToken()
    {
       return $this->apiTokenService->removeTokens(Auth::user());
    }
}
