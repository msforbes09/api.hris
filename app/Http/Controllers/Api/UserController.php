<?php

namespace App\Http\Controllers\Api;

use App\Contracts\IUser;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class UserController extends Controller
{
    protected $iUser;

    public function __construct(IUser $iUser)
    {
        $this->iUser = $iUser;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', $this->iUser->model);

        $usersPePage = $this->iUser->all();

        return ResponseBuilder::asSuccess(200)
            ->withData($usersPePage)
            ->build();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $this->authorize('create', $this->iUser->model);

        $validatedRequest = $request->validated();

        $newUser = $this->iUser->store($validatedRequest);

        $newUser->sendWelcomeNotification($validatedRequest['password']);
        
        Log::info(__('logging.created_user', [
            'name' => Auth::user()->name,
            'id' => Auth::user()->id,
            'created_name' => $newUser->name,
            'created_id' => $newUser->id,
        ]));

        return ResponseBuilder::asSuccess(200)
            ->withMessage('User successfully created.')
            ->withData($newUser)
            ->build();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = $this->iUser->getById($id);

        $this->authorize('view', $user);

        return ResponseBuilder::asSuccess(200)
            ->withData($user)
            ->build();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {   
        $this->authorize('update', $this->iUser->getById($id));

        $validatedRequest = $request->validated();

        $updatedUser = $this->iUser->update($validatedRequest, $id);

        Log::info(__('logging.updated_user', [
            'name' => Auth::user()->name,
            'id' => Auth::user()->id,
            'updated_name' => $updatedUser->name,
            'updated_id' => $updatedUser->id,
        ]));

        return ResponseBuilder::asSuccess(200)
            ->withMessage('User successfully updated.')
            ->withData($updatedUser)
            ->build();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deletedUser = $this->iUser->getById($id);

        $this->authorize('delete', $deletedUser);

        if($this->iUser->destroy($id))
        {
             Log::info(__('logging.deleted_user', [
                'name' => Auth::user()->name,
                'id' => Auth::user()->id,
                'deleted_name' => $deletedUser->name,
                'deleted_id' => $deletedUser->id,
            ]));
             
            return ResponseBuilder::asSuccess(200)
                ->withMessage('User successfully deleted.')
                ->build();
        }
    }
}
