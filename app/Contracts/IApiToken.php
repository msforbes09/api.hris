<?php

namespace App\Contracts;

interface IApiToken
{
    public function user();
    
    public function getToken($request);

    public function removeTokens($user);
}
