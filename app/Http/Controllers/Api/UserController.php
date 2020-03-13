<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Module;
use Carbon\Carbon;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Helpers\SearchFilterPagination;

class UserController extends Controller
{
    protected $module;

    public function __construct()
    {
        $this->module = Module::where('code', 'user')->first();
    }

    /**
     * Return a paginated list of Users
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('allows', [$this->module, 'view']);

        return SearchFilterPagination::get(User::with(['branch', 'userType']));
    }

    /**
     * Creates a User
     * and returns an array as response to client
     *
     * @param UserRequest $request
     * @return array
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(UserRequest $request)
    {
        $this->authorize('allows', [$this->module, 'create']);

        $rawPassword = Carbon::now()->timestamp;

        $user = User::create(
            $request
            ->merge(['password' => bcrypt($rawPassword)])
            ->only(User::getModel()->getFillable())
        );

        $user->sendWelcomeNotification($rawPassword);

        return $this->responseMessage($user, 'created');
    }

    /**
     * Return a single User as response to client
     *
     * @param User $user
     * @return User
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(User $user)
    {
        $this->authorize('allows', [$this->module, 'show']);

        return $user->load(['branch', 'userType.moduleActions']);
    }

    /**
     * Updates a User
     * and returns an array as response to client
     *
     * @param UserRequest $request
     * @param User $user
     * @return array
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UserRequest $request, User $user)
    {
        $this->authorize('allows', [$this->module, 'update']);

        $user->update(
            $request
            ->only($user->getFillable())
        );

        return $this->responseMessage($user, 'updated');
    }

    /**
     * User deletion is FORBIDDEN
     */
    public function destroy()
    {
        abort(403);
    }

    /**
     * Logs request action
     * and creates an array response
     *
     * @param User $user
     * @param $action
     * @return array
     */
    public function responseMessage(User $user, $action)
    {
        $responsible = request()->user()->username;

        Log::info("{$responsible} has ${action} a User", ['data' => $user]);

        return [
            'message' => "Successfully {$action} user.",
            'user' => $user
        ];
    }
}
