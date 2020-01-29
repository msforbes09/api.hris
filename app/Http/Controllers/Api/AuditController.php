<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Audit;
use App\Http\Controllers\Controller;

class AuditController extends Controller
{
    public function auditByUser(User $user)
    {
        return Audit::where('user_id', $user->id)->get();
    }
}