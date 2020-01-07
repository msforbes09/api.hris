<?php

namespace App\Repositories;

use App\User;
use App\Contracts\IUser;
use Illuminate\Support\Arr;
use App\Contracts\PaginationQuery;
use Illuminate\Support\Facades\Hash;

class UserRepository extends PaginationQuery implements IUser
{
    public $model;

    public function __construct(User $user)
    {
        $this->model = $user;
        $this->allowedOrderByFilter = ['email', 'username', 'user_type', 'name', 'active'];
    }

    public function all() {
        $paginationQuery = $this->getPaginationParams();

        $status = $this->userStatusQuery();
        $search = request('search') ?? '';

        $query = $this->allQuery($status, $search);

        $users = $query->orderBy($paginationQuery->orderBy, $paginationQuery->order)
            ->paginate($paginationQuery->perPage);       

        return $users->toArray();
    }

    protected function allQuery($status, $search)
    {
        $query = User::select('users.*', 'user_types.name as user_type')
            ->leftJoin('user_types', 'user_types.id', 'users.user_type_id')
            ->whereIn('users.active', $status)
            ->where(function($query) use ($search){
                $query->where('users.name', 'like', '%' . $search . '%')
                    ->orWhere('users.username', 'like', '%' . $search . '%')
                    ->orWhere('users.email', 'like', '%' . $search . '%')
                    ->orWhere('user_types.name', $search);
            });
        
        return $query;
    }

    protected function userStatusQuery()
    {
        $status = request('status');

        switch ($status) {
            case 'active':
                return [1];
                break;
            case 'inactive':
                return [0];
                break;
            default:
                return [1,0];
                break;
        }
    }

    public function getById($id) {
        return User::findOrFail($id);
    }

    public function getByUsername($username)
    {
        $user = User::where('username', $username)->first();

        return $user;
    }

    public function getByEmail($email)
    {
        $user = User::where('email', $email)->first();

        return $user;
    }

    public function getWithUserType($id)
    {
        $user = User::with('user_type')->findOrFail($id);

        return $user;
    }

    public function store($request)
    {
        $request['password'] = Hash::make($request['password']);

        $user = User::create($request);

        return $user;
    }

    public function update($request, $id)
    {
        $user = $this->getById($id);

        $user->fill($request);

        if(Arr::has($request, 'password'))
        {
            $user->password = Hash::make($request['password']);
        }

        $user->save();

        return $user;
    }

    public function destroy($id)
    {
        $user = $this->getById($id);

        if($user->delete())
        {
            return true;
        }

        return false;
    }
}
