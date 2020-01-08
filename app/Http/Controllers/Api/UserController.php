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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', User::class);


        return response()->json(User::with('user_type')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\UserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $this->authorize('create', User::class);


        $password = getRandomPassword();

        $data = $request->validated();

        $data['password'] = bcrypt($password);


        $newUser = User::create($data);


        $newUser->sendWelcomeNotification($password);


        appLog('Create_User', auth()->user()->id, $newUser);


        return response()->json([
            'message' => 'User successfully created.',
            'data' => [
                'user' => $newUser
            ]
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);


        $user->user_type;


        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\UserRequest  $request
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {
        $this->authorize('update', $user);


        $data = $request->validated();

        if(Arr::has($data, 'password'))
        {
            $data['password'] = bcrypt($data['password']);
        }


        $user->fill($data);

        $user->save();


        appLog('Updated_User', auth()->user()->id, $user);


        return response()->json([
            'message' => 'User successfully updated.',
            'data' => [
                'user' => $user
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        abort(403);
    }


    public function accesses(User $user)
    {
        $this->authorize('view', $user);

        $accesses = $user->user_type->module_actions;

        $message = $accesses->count()
                    ? 'Successfully retrieved user accesses.'
                    : 'No accesses found.';


        return response()->json([
            'message' =>  $message,
            'data' => $accesses
        ]);
    }
}
