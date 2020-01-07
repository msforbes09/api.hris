<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Services\UserServices;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class UserController extends UserServices
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $this->authorize('create', User::class);


        $password = $this->getRandomPassword();

        $data = $request->validated();

        $data['password'] = bcrypt($password);


        $newUser = User::create($data);


        $this->sendWelcome($newUser, $password);


        Log::info(__('logging.created_user', [
            'name' => Auth::user()->name,
            'id' => Auth::user()->id,
            'created_name' => $newUser->name,
            'created_id' => $newUser->id,
        ]));


        return response()->json($newUser);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
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


        Log::info(__('logging.updated_user', [
            'name' => Auth::user()->name,
            'id' => Auth::user()->id,
            'updated_name' => $user->name,
            'updated_id' => $user->id,
        ]));


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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        abort(403);
    }
}
