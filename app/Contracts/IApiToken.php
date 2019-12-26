<?php

namespace App\Contracts;

interface IApiToken
{
    public function getToken($request);

    public function removeTokens($user);
}