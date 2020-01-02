<?php

namespace App\Http\Controllers\Api;

use App\Contracts\IUserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class UserTypeController extends Controller
{
    protected $iUserType;

    public function __construct(IUserType $iUserType)
    {
        $this->iUserType = $iUserType;
    }

    public function index()
    {
        $userTypes = $this->iUserType->all();

        return ResponseBuilder::asSuccess(200)
            ->withData($userTypes)
            ->build();
    }
}
