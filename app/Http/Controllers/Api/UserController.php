<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', User::class);

        return User::with('userType')->get();
    }

    public function store(UserRequest $request)
    {
        $this->authorize('create', User::class);

        $password = getRandomPassword();

        $user = User::create(request()->merge(['password' => bcrypt($password)])->toArray());

        $user->sendWelcomeNotification($password);

        appLog('Create_User', auth()->user()->id, $user);

        return response()->json([
            'message' => 'Successfully created user.',
            'user' => $user
        ]);
    }

    public function show(User $user)
    {
        $this->authorize('view', $user);

        $user->userType->moduleActions;

        return $user;
    }

    public function update(UserRequest $request, User $user)
    {
        $this->authorize('update', $user);

        $user->fill(request()->toArray());

        $user->save();

        appLog('Updated_User', auth()->user()->id, $user);

        return response()->json([
            'message' => 'Successfully updated user.',
            'user' => $user
        ]);
    }

    public function destroy(User $user)
    {
        abort(403);
    }
}
