<?php

namespace App\Http\Controllers\Api;

use App\Contracts\IUser;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
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
        $validatedRequest = $request->validated();

        $newUser = $this->iUser->store($validatedRequest);
        
        return ResponseBuilder::asSuccess(200)
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
        $user = $this->iUser->getUserbyId($id);

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
        $validatedRequest = $request->validated();

        $updatedUser = $this->iUser->update($validatedRequest, $id);

        return ResponseBuilder::asSuccess(200)
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
        if($this->iUser->destroy($id))
        {
            return ResponseBuilder::asSuccess(200)
                ->withMessage('User successfully deleted.')
                ->build();
        }
    }
}
