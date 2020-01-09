<?php

namespace App\Http\Controllers\Api;

use App\Client;
use App\ClientBranch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClientBranchRequest;

class ClientBranchController extends Controller
{
    public function index(Client $client)
    {
       return $client->branches;
    }

    public function store(ClientBranchRequest $request, Client $client)
    {
        $branch = $client->branches()->create(request()->toArray());

        return response()->json([
            'message' => 'Successfully created client branch.',
            'branch' => $branch
        ]);
    }

    public function show(Client $client, ClientBranch $branch)
    {
        return $branch;
    }

    public function update(ClientBranchRequest $request, Client $client, ClientBranch $branch)
    {
        $branch->fill(request()->toArray());

        $branch->save();

        return response()->json([
            'message' => 'Successfuly updated client branch.',
            'branch' => $branch
        ]);
    }

    public function destroy(Client $client, ClientBranch $branch)
    {
        abort(403);
    }
}
