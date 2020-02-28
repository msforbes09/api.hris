<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Module;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected $module;

    public function __construct()
    {
        $this->module = Module::where('code', 'user')->first();
    }

    public function index()
    {
        $this->authorize('allows', [$this->module, 'view']);

        return User::with(['branch', 'userType'])->orderBy('id', 'desc')->get();
    }

    public function store(UserRequest $request)
    {
        $this->authorize('allows', [$this->module, 'create']);

        $rawPassword = Carbon::now()->timestamp;

        $user = User::create(request()
            ->merge(['password' => bcrypt($rawPassword)])
            ->only(User::getModel()->getFillable())
        );

        $user->sendWelcomeNotification($rawPassword);

        Log::info(auth()->user()->username . ' has created a User.', ['data' => $user]);

        return [
            'message' => 'Successfully created user.',
            'user' => $user
        ];
    }

    public function show(User $user)
    {
        $this->authorize('allows', [$this->module, 'show']);

        return $user->load(['branch', 'userType.moduleActions']);
    }

    public function update(UserRequest $request, User $user)
    {
        $this->authorize('allows', [$this->module, 'update']);

        $user->update(request()->only($user->getFillable()));

        Log::info(auth()->user()->username . ' has updated a User', ['data' => $user]);

        return [
            'message' => 'Successfully updated user.',
            'user' => $user
        ];
    }

    public function destroy(User $user)
    {
        abort(403);
    }
}
